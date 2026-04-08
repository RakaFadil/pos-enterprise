<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // A. Menampilkan Form
    public function loginForm() {
        // Jika sudah login, halangi buka halaman login lagi
        if (Auth::check()) {
            return Auth::user()->role === 'admin' ? redirect('/') : redirect('/transaksi/baru');
        }
        return view('auth.login');
    }

    // B. Proses Eksekusi Login
    public function processLogin(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // "Auth::attempt($credentials)" otomatis mencocokkan email & password Hash di Database

        if(Auth::attempt($credentials)) {
            // Jika sukses, buatkan Sesi (Sistem Session Laravel pengganti $_SESSION)
            $request->session()->regenerate();

            // Logika Pembelokan: Admin ke Dashboard, Kasir ke halaman Transaksi
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/');
            } else {
                return redirect()->intended('/transaksi/baru');
            }
        }

        // Kalau password salah, kembali ke form login
        return back()->with('error', 'Kombinasi Email dan Password salah.');
    }

    // C. Proses Logout Kelua
    public function logout(Request $request) {
        Auth::logout();

        // Hancurkan Sesi dengan bersih
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout dari sistem POS!');


    }
}
