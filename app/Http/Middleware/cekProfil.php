<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\user;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class cekProfil
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::where('id', auth()->user()->id)
            ->join('pegawai', 'users.nip', '=', 'pegawai.nip')
            ->first();

        if (empty($user->nip_atasan) || empty($user->id_jabatan) || empty($user->id_bidang)) {
            return redirect()->route('viewEditProfil')->with('error', 'Lengkapi data diri terlebih dahulu!');
        }

        return $next($request);
    }
}
