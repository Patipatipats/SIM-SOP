<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        $captcha = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 5);
        Session::put('captcha', $captcha);

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required',
            'password' => 'required',
            'captcha'  => 'required'
        ]);

        // ================= CAPTCHA =================
        if ($request->captcha !== Session::get('captcha')) {
            return back()->withErrors(['captcha' => 'Captcha salah'])->withInput();
        }

        // ================= LOGIN FIELD =================
        $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $credentials = [
            $loginField => $request->email,
            'password'  => $request->password
        ];

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            // ================= CEK STATUS USER =================
            $profil = DB::table('profil_pengguna')
                ->where('user_id', $user->id)
                ->first();

            if (!$profil || $profil->is_aktif != 1) {
                Auth::logout();
                return back()->withErrors(['email' => 'User tidak aktif']);
            }

            // ================= LOG AKTIVITAS =================
            DB::table('log_aktivitas')->insert([
                'user_id'     => $user->id,
                'modul'       => 'AUTH',
                'aktivitas'   => 'Login ke sistem',
                'referensi_id'=> null,
                'created_at'  => now()
            ]);

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email/Username atau password salah'
        ])->withInput();
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            DB::table('log_aktivitas')->insert([
                'user_id'    => Auth::id(),
                'modul'      => 'AUTH',
                'aktivitas'  => 'Logout dari sistem',
                'created_at' => now()
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}