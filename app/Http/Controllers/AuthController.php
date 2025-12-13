<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\VerificationMail;
use Exception;

class AuthController extends Controller
{
    // ==================== 📋 FORM REGISTER CUSTOMER ====================
    public function showRegister()
    {
        return view('auth.register');
    }

    // ==================== 🧾 PROSES REGISTER CUSTOMER ====================
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => 0,
            'role' => 'customer',
        ]);

        $token = base64_encode($user->email);

        try {
            Mail::to($user->email)->send(new VerificationMail($user->name, $token));

            return redirect('/login')->with(
                'status',
                'Pendaftaran berhasil! Silakan cek email untuk verifikasi akun Anda.'
            );
        } catch (Exception $e) {
            return redirect('/login')->with(
                'error',
                'Pendaftaran berhasil, tapi gagal mengirim email. (' . $e->getMessage() . ')'
            );
        }
    }

    public function showRegisterPekerja()
    {
        return view('auth.register_pekerja');
    }

    
    public function registerPekerja(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        do {
            $kodePegawai = 'SCM' . strtoupper(Str::random(6));
            $exists = User::where('kode_pegawai', $kodePegawai)->exists();
        } while ($exists);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => 0,
            'role' => 'pekerja',
            'kode_pegawai' => $kodePegawai,
        ]);

        $token = base64_encode($user->email);

        try {
            Mail::to($user->email)->send(new VerificationMail($user->name, $token, $kodePegawai));

            return redirect('/login/pekerja')->with(
                'status',
                "Pendaftaran berhasil! Kode Pegawai Anda: {$kodePegawai}. Silakan cek email Anda untuk verifikasi."
            );
        } catch (Exception $e) {
            return redirect('/login/pekerja')->with(
                'error',
                'Akun dibuat tapi gagal mengirim email. (' . $e->getMessage() . ')'
            );
        }
    }

    
    public function verify(Request $request)
    {
        $email = base64_decode($request->token);
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->is_active = 1;
            $user->email_verified_at = now();
            $user->save();

            return redirect('/verify-success')->with('success', 'Verifikasi berhasil! Akun Anda sudah aktif.');
        }

        return redirect('/login')->with('error', 'Token tidak valid atau akun tidak ditemukan.');
    }

    // ==================== ✅ VERIFIKASI SUKSES ====================
    public function verifySuccess()
    {
        return view('auth.verify_success');
    }

    // ==================== 🔑 FORM LOGIN CUSTOMER ====================
    public function showLogin()
    {
        return view('auth.login');
    }

    // ==================== 🔐 PROSES LOGIN CUSTOMER ====================
    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');
        $user = User::where('name', $credentials['name'])->first();

        if (!$user) {
            return back()->with('error', 'Data tidak ditemukan. Silakan registrasi terlebih dahulu.');
        }

        if ($user->is_active == 0) {
            return back()->with('error', 'Akun belum aktif! Silakan verifikasi email Anda.');
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return back()->with('error', 'Password salah.');
        }

        Auth::login($user);

        return $user->role === 'pekerja'
            ? redirect('/admin/dashboard')->with('success', 'Login berhasil sebagai pekerja ☕')
            : redirect('/dashboard')->with('success', 'Login berhasil!');
    }

    // ==================== 👷‍♂️ FORM LOGIN PEKERJA ====================
    public function showLoginPekerja()
    {
        return view('auth.login_pekerja');
    }

    // ==================== 🔐 PROSES LOGIN PEKERJA ====================
    public function loginPekerja(Request $request)
    {
        $credentials = $request->only('name', 'password', 'kode_pegawai');

        $user = User::where('name', $credentials['name'])
            ->where('role', 'pekerja')
            ->where('kode_pegawai', $credentials['kode_pegawai'])
            ->first();

        if (!$user) {
            return back()->with('error', 'Data tidak ditemukan atau kode pegawai salah.');
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return back()->with('error', 'Password salah.');
        }

        if ($user->is_active == 0) {
            return back()->with('error', 'Akun belum aktif. Silakan verifikasi terlebih dahulu.');
        }

        Auth::login($user);
        return redirect('/admin/dashboard')->with('success', 'Login berhasil! Selamat bekerja ☕');
    }

    // ==================== 🚪 LOGOUT ====================
    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('status', 'Kamu sudah logout.');
    }
}
