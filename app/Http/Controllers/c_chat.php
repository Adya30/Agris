<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Events\MessageSent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class c_chat extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin) {
            $users = User::where('isAdmin', false)
                ->where('isActive', true)
                ->get();
            return view('admin.chat.index', compact('users'));
        }

        $admin = User::where('isAdmin', true)->first();
        $chats = Chat::where(function($q) use ($user, $admin) {
                $q->where(function($inner) use ($user, $admin) {
                    $inner->where('id_pengirim', $user->id)->where('id_penerima', $admin->id);
                })->orWhere(function($inner) use ($user, $admin) {
                    $inner->where('id_pengirim', $admin->id)->where('id_penerima', $user->id);
                });
            })
            ->orWhere('id_penerima', 'GLOBAL')
            ->orderBy('waktu_chat', 'asc')
            ->get();

        return view('agen.chat.index', compact('chats', 'admin'));
    }

    public function show($id)
    {
        $user = Auth::user();

        if ($id === 'GLOBAL') {
            $chats = Chat::where('id_penerima', 'GLOBAL')
                ->orderBy('waktu_chat', 'asc')
                ->get();
            return response()->json(['target' => ['id' => 'GLOBAL', 'name' => 'PENGUMUMAN GLOBAL'], 'chats' => $chats]);
        }

        $targetUser = User::where('id', $id)->where('isActive', true)->firstOrFail();

        $chats = Chat::where(function($q) use ($user, $targetUser) {
                $q->where('id_pengirim', $user->id)->where('id_penerima', $targetUser->id);
            })->orWhere(function($q) use ($user, $targetUser) {
                $q->where('id_pengirim', $targetUser->id)->where('id_penerima', $user->id);
            })->orderBy('waktu_chat', 'asc')
            ->get();

        Chat::where('id_pengirim', $targetUser->id)
            ->where('id_penerima', $user->id)
            ->update(['status' => 'dibaca']);

        return response()->json([
            'target' => $targetUser,
            'chats' => $chats
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penerima' => 'required',
            'pesan' => 'required_without:foto_chat',
            'foto_chat' => 'nullable|image|max:10240'
        ]);

        try {
            $base64Image = null;
            if ($request->hasFile('foto_chat')) {
                $file = $request->file('foto_chat');
                $base64Image = 'data:' . $file->getClientMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
            }

            $chat = Chat::create([
                'id_pengirim' => Auth::id(),
                'id_penerima' => (string) $request->id_penerima,
                'pesan' => $request->pesan ?? '',
                'foto_chat' => $base64Image,
                'status' => 'terkirim',
                'waktu_chat' => now()
            ]);

            broadcast(new MessageSent($chat))->toOthers();

            return response()->json([
                'id' => $chat->id,
                'id_pengirim' => (string) $chat->id_pengirim,
                'id_penerima' => (string) $chat->id_penerima,
                'pesan' => $chat->pesan,
                'foto_chat' => $chat->foto_chat,
                'waktu_chat' => $chat->waktu_chat->format('Y-m-d H:i:s'),
                'status' => $chat->status
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $chat = Chat::where('id', $id)
                ->where('id_pengirim', Auth::id())
                ->firstOrFail();

            $chat->update([
                'pesan' => 'Pesan ini telah dihapus',
                'foto_chat' => null,
            ]);

            broadcast(new MessageSent($chat))->toOthers();

            return response()->json(['success' => true, 'chat' => $chat]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
