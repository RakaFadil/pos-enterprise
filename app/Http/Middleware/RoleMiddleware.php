<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */

    // Fungsi ini akan mengecek apakah jabatannya sesuai dengan syarat rutenya
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Jika rolenya tidak cocok dengan yang dipersyaratkan
        if (auth()->user()->role !== $role) {
            return redirect('/transaksi/baru')->with('error', 'Akses Ditolak! Cuma Admin yang boleh masuk.');
        }
        return $next($request);
    }
}
