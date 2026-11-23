<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\User;
use App\Rules\SecureImageFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class c_chat extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin) {
            $users = User::isAdmin(false)->get()->map(function ($u) {
                return [
                    'id' => $u->id,
                    'namaLengkap' => $u->namaLengkap,
                    'isActive' => $u->isActive,
                    'fotoProfil' => storage_url($u->fotoProfil),
                ];
            });

            return view('admin.chat.index', compact('users'));
        }

        $admin = User::isAdmin(true)->first();
        $chats = Chat::query()->where(function ($q) use ($user, $admin) {
            $q->where(function ($inner) use ($user, $admin) {
                $inner->where('id_pengirim', $user->id)->where('id_penerima', $admin->id);
            })->orWhere(function ($inner) use ($user, $admin) {
                $inner->where('id_pengirim', $admin->id)->where('id_penerima', $user->id);
            });
        })->orWhere('id_penerima', 'GLOBAL')->orderBy('waktu_chat', 'asc')->get();

        $updated = Chat::whereIdPengirim($admin->id)->whereIdPenerima($user->id)->whereStatus('terkirim')->update(['status' => 'dibaca']);

        if ($updated > 0) {
            broadcast(new MessageSent(['id_penerima' => $admin->id, 'id_pengirim' => $user->id, 'status' => 'dibaca'], false, true))->toOthers();
        }

        return view('agen.chat.index', compact('chats', 'admin'));
    }

    public function show(string $id)
    {
        $user = Auth::user();
        if ($id === 'GLOBAL') {
            $chats = Chat::whereIdPenerima('GLOBAL')->orderBy('waktu_chat', 'asc')->get();

            return response()->json(['target' => ['id' => 'GLOBAL', 'name' => 'PENGUMUMAN GLOBAL'], 'chats' => $chats]);
        }

        $targetUser = User::findOrFail($id);

        if (! $user->isAdmin && ! $targetUser->isAdmin) {
            return response()->json(['error' => 'Akses ditolak: Pengguna hanya dapat mengakses percakapan dengan administrator.'], 403);
        }

        $chats = Chat::query()->where(function ($q) use ($user, $targetUser) {
            $q->where(function ($inner) use ($user, $targetUser) {
                $inner->where('id_pengirim', $user->id)->where('id_penerima', $targetUser->id);
            })->orWhere(function ($inner) use ($user, $targetUser) {
                $inner->where('id_pengirim', $targetUser->id)->where('id_penerima', $user->id);
            });
        })->orderBy('waktu_chat', 'asc')->get();

        $updated = Chat::whereIdPengirim($targetUser->id)->whereIdPenerima($user->id)->whereStatus('terkirim')->update(['status' => 'dibaca']);

        if ($updated > 0) {
            broadcast(new MessageSent(['id_penerima' => $targetUser->id, 'id_pengirim' => $user->id, 'status' => 'dibaca'], false, true))->toOthers();
        }

        $targetSummary = [
            'id' => $targetUser->id,
            'namaLengkap' => $targetUser->namaLengkap,
            'fotoProfil' => storage_url($targetUser->fotoProfil),
            'isActive' => $targetUser->isActive,
            'isAdmin' => $targetUser->isAdmin,
        ];

        return response()->json(['target' => $targetSummary, 'chats' => $chats]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penerima' => 'required',
            'pesan' => 'required_without:foto_chat|nullable|string|max:1000',
            'foto_chat' => ['nullable', new SecureImageFile(10240)],
        ], [
            'pesan.max' => 'Pesan maksimal 1.000 karakter.',
        ]);

        $user = Auth::user();

        // 1. Anti-spam cooldown (minimal jeda 2 detik antar pesan untuk non-admin)
        if (! $user->isAdmin) {
            $cooldownKey = 'chat_cooldown_'.$user->id;
            if (Cache::has($cooldownKey)) {
                return response()->json([
                    'error' => 'Anda mengirim pesan terlalu cepat. Mohon tunggu 2 detik.',
                ], 429);
            }
        }

        if ($request->id_penerima === 'GLOBAL') {
            if (! $user->isAdmin) {
                return response()->json(['error' => 'Hanya administrator yang diizinkan mengirim pengumuman global.'], 403);
            }
        } elseif (! $user->isAdmin) {
            $targetUser = User::find($request->id_penerima);
            if (! $targetUser || ! $targetUser->isAdmin) {
                return response()->json(['error' => 'Agen hanya dapat mengirim pesan kepada administrator.'], 403);
            }
        }

        // 2. Sanitasi konten teks (strip tags untuk mencegah injeksi script/HTML)
        $pesan = $request->pesan ? trim(strip_tags($request->pesan)) : null;
        if (empty($pesan) && ! $request->hasFile('foto_chat')) {
            return response()->json(['error' => 'Pesan tidak boleh kosong.'], 422);
        }

        // 3. Anti-duplicate spam (mencegah pesan identik dikirim berulang dalam 15 detik)
        if (! $user->isAdmin && ! empty($pesan)) {
            $dupKey = 'chat_dup_'.$user->id.'_'.md5($request->id_penerima.'_'.$pesan);
            if (Cache::has($dupKey)) {
                return response()->json([
                    'error' => 'Pesan duplikat terdeteksi. Mohon jangan mengirim pesan yang sama berulang kali.',
                ], 422);
            }
        }

        // 4. Pembatasan kuota pesan harian non-admin untuk melindungi kuota Pusher dari bot
        if (! $user->isAdmin) {
            $dailyCountKey = 'chat_daily_count_'.$user->id.'_'.now()->toDateString();
            $dailyCount = (int) Cache::get($dailyCountKey, 0);
            if ($dailyCount >= 100) {
                return response()->json([
                    'error' => 'Batas pengiriman pesan harian telah tercapai (maksimal 100 pesan/hari). Silakan lanjutkan besok atau hubungi admin.',
                ], 429);
            }
        }

        try {
            $path = null;
            $disk = config('filesystems.default', 'public');
            if ($request->hasFile('foto_chat')) {
                $path = $request->file('foto_chat')->store('chats', $disk);
            }

            $chat = Chat::create([
                'id_pengirim' => Auth::id(),
                'id_penerima' => $request->id_penerima,
                'pesan' => $pesan,
                'foto_chat' => $path,
                'status' => 'terkirim',
                'waktu_chat' => now(),
            ]);

            // Set rate limit & anti-spam markers
            if (! $user->isAdmin) {
                Cache::put('chat_cooldown_'.$user->id, true, 2);
                if (! empty($pesan)) {
                    Cache::put('chat_dup_'.$user->id.'_'.md5($request->id_penerima.'_'.$pesan), true, 15);
                }
                $dailyCountKey = 'chat_daily_count_'.$user->id.'_'.now()->toDateString();
                Cache::put($dailyCountKey, $dailyCount + 1, now()->endOfDay());
            }

            $broadcastData = $chat->toArray();
            $broadcastData['foto_chat'] = storage_url($chat->foto_chat);

            broadcast(new MessageSent($broadcastData, false))->toOthers();

            return response()->json($broadcastData);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            /** @var Chat $chat */
            $chat = Chat::whereId($id)->whereIdPengirim(Auth::id())->firstOrFail();
            broadcast(new MessageSent(['id' => $chat->id, 'id_penerima' => $chat->id_penerima, 'id_pengirim' => $chat->id_pengirim], true))->toOthers();
            $disk = config('filesystems.default', 'public');
            if ($chat->foto_chat && Storage::disk($disk)->exists($chat->foto_chat)) {
                Storage::disk($disk)->delete($chat->foto_chat);
            }
            Chat::whereId($chat->id)->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function recordChatbotUsage(Request $request)
    {
        $userId = Auth::id() ?? ($request->hasSession() ? $request->session()->getId() : $request->ip());
        $dateKey = now()->toDateString();
        $cacheKey = 'chatbot_used_'.$userId.'_'.$dateKey;

        $alreadyUsed = Cache::has($cacheKey);

        if (! $alreadyUsed) {
            $secondsUntilMidnight = max(60, now()->diffInSeconds(now()->endOfDay()));
            Cache::put($cacheKey, now()->toIso8601String(), $secondsUntilMidnight);
            Cache::increment('chatbot_total_used_'.$userId);
        }

        return response()->json([
            'success' => true,
            'quota_exceeded' => true,
            'message' => 'Batas pengiriman pesan chatbot (1 pesan per hari) telah tercapai.',
            'resets_at' => now()->endOfDay()->toIso8601String(),
        ]);
    }
}
