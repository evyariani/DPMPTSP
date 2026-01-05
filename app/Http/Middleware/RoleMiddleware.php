<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Cek jika user belum login
        if (!session()->has('user')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        $userLevel = session('user')['level'];
        
        // Jika role yang diizinkan adalah multiple roles (dipisah dengan |)
        $allowedRoles = explode('|', $role);
        
        if (!in_array($userLevel, $allowedRoles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}