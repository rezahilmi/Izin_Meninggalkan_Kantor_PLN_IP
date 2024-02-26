<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class SesiController extends Controller
{
    function index()
    {

    return view('login'); // Tampilkan halaman login jika pengguna belum masuk
    }
    function admin()
    {
        if (auth()->check()) {
        // Jika pengguna sudah masuk, arahkan mereka ke halaman yang sesuai dengan rolenya
        switch (auth()->user()->role) {
            case 'pegawai':
                return redirect('/dashboard-pegawai');
            case 'atasan':
                return redirect('/dashboard-atasan');
            case 'sdm':
                return redirect('/dashboard-sdm');
            case 'cuti':
                return redirect('/dashboard-atasan');
            case 'keamanan':
                return redirect('/keamanan/laporanIzin');
            default:
                return redirect('/logout');
        }
    }
    return view('login');
    }

    function login(Request $request)
    {
        $request->validate([
            'credential' => 'required',
            'password' => 'required',
        ], [
            'credential.required' => 'Email/NIP wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $infologin = [
            'email' => $request->credential,
            'password' => $request->password,
        ];
        $redirect = $request->input('redirect_back', '/');

        $emailExists = isset($infologin['email']) ? User::where('email', $infologin['email'])->exists() : false;

        if (!$emailExists) {
            return redirect()->back()->withErrors('Email belum terdaftar')->withInput();
        }

        if (Auth::attempt($infologin)) {
            $user = Auth::user();

            // Setelah melakukan pengecekan, lanjutkan dengan rute sesuai peran
            if ($user->role == 'pegawai') {
                return redirect($redirect);
                unset($redirect);
            } elseif ($user->role == 'atasan') {
                return redirect($redirect);
            } elseif ($user->role == 'sdm') {
                return redirect($redirect);
            } elseif ($user->role == 'cuti') {
                return redirect($redirect);
            } elseif ($user->role == 'keamanan') {
                return redirect($redirect);
            }
        } else {
            return redirect()->back()->withErrors('Email/NIP dan Password yang dimasukkan tidak sesuai')->withInput();
        }
    }

    function registerView()
    {
        return view('register');
    }


    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email', // Added email validation
            'password' => 'required|min:6|max:8',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email harus memiliki format yang valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal harus memiliki 6 karakter.',
            'password.max' => 'Password maksimal harus memiliki 8 karakter.',
        ]);

        User::create([
            'nip' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => 'karyawan',
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat!');
    }

    function logout()
    {
        Auth::logout();
        return redirect('');
    }
}
