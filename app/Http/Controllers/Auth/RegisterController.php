<?php

namespace App\Http\Controllers\Auth;

use App\Models\Province;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        $provinces = Province::orderBy('name')->get();
        return view('auth.register', compact('provinces'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(6)],
            'phone' => ['nullable', 'string', 'max:20'],
            'province_id' => ['required', 'exists:reg_provinces,id'],
            'regency_id' => ['required', 'exists:reg_regencies,id'],
            'district_id' => ['required', 'exists:reg_districts,id'],
            'address' => ['nullable', 'string', 'max:500'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.',
            'province_id.required' => 'Provinsi wajib dipilih.',
            'regency_id.required' => 'Kota/Kabupaten wajib dipilih.',
            'district_id.required' => 'Kecamatan wajib dipilih.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'photo.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'phone' => $request->phone,
            'address' => $request->address,
            'province_id' => $request->province_id,
            'regency_id' => $request->regency_id,
            'district_id' => $request->district_id,
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->update(['photo' => $path]);
        }

        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name . '!');
    }

    private function redirectByRole()
    {
        return Auth::user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    }
}
