<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Temp_PasswordController extends Controller
{
    /**
     * Tampilkan halaman list user untuk manajemen temp password
     */
    public function index()
    {
        // Panggil data user (sama kayak manajemen user, tapi kita tampilin lebih simpel)
        $users = User::with('role')
                     ->orderBy('id', 'desc')
                     ->paginate(10);

        return view('manajemen_pengguna.temp_password.index', compact('users'));
    }

    /**
     * Fungsi buat Generate Random Password
     */
    public function generate($id)
    {
        $user = User::findOrFail($id);

        // 1. Generate 8 karakter acak (huruf & angka)
        $randomPassword = Str::random(8);

        // 2. Update ke database pake Hash biar aman
        $user->update([
            'password' => Hash::make($randomPassword)
        ]);

        // 3. Balikin ke halaman bawa data password aslinya (Plain text) biar bisa dicopy Admin
        return redirect()->back()
            ->with('temp_password', $randomPassword)
            ->with('user_nama', $user->nama);
    }
}