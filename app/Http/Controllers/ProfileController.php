<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil
     */
    public function index()
    {
        $user = Auth::user();
        return view('penumpang.profile.index', compact('user'));
    }

    /**
     * Update profil (nama, no_telp, alamat)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'nama'    => 'required|string|max:255',
            'no_telp' => 'required|string|max:20|unique:users,no_telp,' . $user->id,
            'alamat'  => 'required|string|max:255',
        ], [
            'nama.required'    => 'Nama lengkap harus diisi.',
            'no_telp.required' => 'Nomor telepon harus diisi.',
            'no_telp.unique'   => 'Nomor telepon sudah digunakan akun lain.',
            'alamat.required'  => 'Alamat harus diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('profile.index')
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'nama'    => $request->nama,
            'no_telp' => $request->no_telp,
            'alamat'  => $request->alamat,
        ]);

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Ganti password
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password'      => 'required',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'current_password.required' => 'Password saat ini harus diisi.',
            'password.min'              => 'Password baru minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('profile.index')
                ->withErrors($validator)
                ->withInput();
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('profile.index')
                ->withErrors(['current_password' => 'Password saat ini tidak benar.'])
                ->withInput();
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('profile.index')->with('success', 'Password berhasil diubah.');
    }
}
