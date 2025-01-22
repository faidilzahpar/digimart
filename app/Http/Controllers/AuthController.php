<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Menangani proses login
    // Menangani proses login
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.index');
            }

            // Login berhasil sebagai user
            return redirect()->intended('/');
        } else {
            // Gagal login
            return back()->withErrors(['username' => 'Username atau password salah.']);
        }
    }


    // Menampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Menangani proses register
    // Menangani proses register
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        // Membuat user baru dengan role 'user'
        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user', // Role default adalah 'user'
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat!');
    }


    


    public function showProfile()
    {
        return view('auth.profile', ['user' => Auth::user()]);
    }

    // Memperbarui data profil (username dan foto)
    public function updateProfile(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|min:3|unique:users,username,' . Auth::id(),
            // Validasi email hanya jika email baru diinput
            'email' => 'nullable|email|unique:users,email,' . Auth::id(),
            'password' => 'nullable|confirmed|min:6',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // validasi file gambar
        ]);

        // Ambil data pengguna yang sedang login
        $user = Auth::user();

        // Update username
        $user->username = $request->username;

        // Jika password diubah
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password); // Enkripsi password baru
        }

        // Proses upload gambar jika ada
        if ($request->hasFile('image')) {
            // Ambil file gambar yang diupload
            $image = $request->file('image');
            
            // Buat nama unik untuk file gambar
            $imageName = time().'.'.$image->getClientOriginalExtension();
            
            // Simpan file gambar ke folder public/profile_images
            $image->move(public_path('profile_images'), $imageName);
            
            // Simpan path gambar di database
            $user->image = 'profile_images/'.$imageName;
        }

        // Simpan perubahan data pengguna
        if ($user instanceof User) {
            $user->save();
        }

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }
    
    public function deleteProfileImage()
    {
        $user = User::find(Auth::id()); // Pastikan user dimuat dari model

        if ($user && $user->image && $user->image !== 'profile_images/defaultpp.png') {
            $imagePath = public_path($user->image);
            if (file_exists($imagePath)) {
                unlink($imagePath); // Hapus file dari folder
            }
            $user->image = null; // Set kolom image ke null
            $user->save();
        }

        return redirect()->route('profile')->with('success', 'Foto profil berhasil dihapus!');
    }



    // Mengubah password pengguna
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = Auth::user();

        // Memastikan password lama yang dimasukkan benar
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        // Mengubah password
        $user->password = bcrypt($request->password); // Enkripsi password baru
        if ($user instanceof User) {
            $user->save();
        }

        return redirect()->route('profile')->with('success', 'Password berhasil diubah!');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }
    public function sendResetLink(Request $request)
    {
        // Mengambil email pengguna yang sudah terdaftar
        $email = Auth::user()->email;

        $status = Password::sendResetLink(
            ['email' => $email]
        );

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', 'Link reset password telah dikirim!')
            : back()->withErrors(['email' => 'Email tidak ditemukan.']);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);
    
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();
    
                // Mengirim email atau notif lainnya jika perlu
            }
        );
    
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Password berhasil diubah!');
        } else {
            return back()->withErrors(['email' => [trans($status)]]);
        }
    }





    // Logout pengguna
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}

