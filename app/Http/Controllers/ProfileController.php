<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load(
            'desa.kecamatan.kabupaten.provinsi'
        );

        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => 'required|min:4|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'noTelp' => 'required|regex:/^[0-9]+$/|unique:users,noTelp,' . $user->id,
            'detailAlamat' => 'nullable|string',

            'password' => [
                'nullable',
                'confirmed',
                PasswordRule::min(8)->mixedCase()->numbers()->symbols()
            ],
        ]);

        // Update basic data
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->noTelp = $validated['noTelp'];
        $user->detailAlamat = $validated['detailAlamat'] ?? null;

        // Update password jika diisi
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Upload foto
        if ($request->hasFile('fotoProfil')) {
            $path = $request->file('fotoProfil')->store('profile', 'public');
            $user->fotoProfil = $path;
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}