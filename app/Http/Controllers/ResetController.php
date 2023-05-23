<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetController extends Controller
{
    public function showReset()
    {
        return view('reset');
    }

    public function resetPassword(Request $request)
    {
        $email = $request->input('email');

        // Lakukan validasi dan pengecekan email di database
        // Jika email valid, lakukan pengaturan token dan mengirim email reset password
        // ...

        // Generate token
        $token = Str::random(60);

        // Simpan token ke dalam tabel user (misalnya, tambahkan kolom "reset_token" pada tabel users)
        $user = User::where('email', $email)->first();
        $user->reset_token = $token;
        $user->save();

        // Redirect ke halaman input new password dengan menyertakan token
        return redirect()->route('input.newpassword', ['token' => $token]);
    }

    public function showInputNewPassword($token)
    {
        // Lakukan pengecekan token di dalam tabel user
        // Jika token valid, tampilkan halaman input new password
        // ...

        return view('inputnewpassword', ['token' => $token]);
    }

public function updatePassword(Request $request)
{
    // Validasi input dan peroleh email dari URL
    $token = $request->token;

    // Periksa apakah token valid dan temukan pengguna berdasarkan token
    $user = User::where('reset_token', $token)->first();

    if ($user) {
        // Perbarui password pengguna
        $user->password = Hash::make($request->password);
        $user->reset_token = null; // Setel token ke null setelah pembaruan password
        $user->save();

        // Redirect atau tindakan lain setelah perbarui password
        // Misalnya, redirect ke halaman login
        return redirect()->route('login')->with('success', 'Password berhasil diperbarui. Silakan login dengan password baru Anda.');
    } else {
        // Token tidak valid atau pengguna tidak ditemukan, lakukan penanganan kesalahan di sini
        // Misalnya, redirect kembali ke halaman input new password dengan pesan kesalahan
        return redirect()->route('input.newpassword', ['token' => $token])->with('error', 'Token tidak valid atau pengguna tidak ditemukan.');

    }
}


}
