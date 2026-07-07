<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        // role default sesuai tombol toggle (nasabah/karyawan)
        $role = $request->query('role', 'nasabah');
        return view('auth.login', compact('role'));
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role' => ['required', 'in:nasabah,karyawan'],
        ]);

        // boleh login pakai email atau username (member_id)
        $user = User::where('role', $data['role'])
            ->where(function ($q) use ($data) {
                $q->where('email', $data['email'])
                  ->orWhere('member_id', $data['email']);
            })
            ->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Kredensial tidak cocok untuk peran ' . $data['role'] . '.',
            ])->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(
            $user->isKaryawan() ? route('karyawan.dashboard') : route('nasabah.dashboard')
        );
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:6'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => 'nasabah',
            'member_id' => 'GGB-' . str_pad((string) (User::max('id') + 1), 4, '0', STR_PAD_LEFT),
            'balance' => 0,
        ]);

        Auth::login($user);
        return redirect()->route('nasabah.dashboard');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Prototipe: instruksi reset disimulasikan (belum terhubung ke email/SMTP).
        return back()->with('status', 'Jika email tersebut terdaftar, instruksi untuk mereset kata sandi telah kami kirimkan. Silakan cek kotak masuk Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing');
    }
}
