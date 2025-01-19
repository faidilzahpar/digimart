@extends('layouts.app')

@section('content')
<div class="container mt-5 d-flex justify-content-center">
    <div class="col-md-6 col-lg-5 shadow p-3 border rounded">
        <h2 class="text-center">Profil</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="text-center">
            <img src="{{ asset(Auth::user()->image) }}" alt="Profile Image" class="img-fluid rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
        </div>
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" readonly>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Foto Profil</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            @if (Auth::user()->image && Auth::user()->image !== 'profile_images/defaultpp.png')
                <form action="{{ route('profile.deleteImage') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 mb-2">Hapus Foto Profil</button>
                </form>
            @endif
            <button type="submit" class="btn btn-primary w-100">Perbarui Profil</button>
        </form>

        <hr>

        <form action="{{ route('profile.changePassword') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="current_password" class="form-label">Password Lama</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ubah Password</button>
        </form>

        <hr>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger w-100">Keluar</button>
        </form>
        <div class="text-center mt-3">
            <a href="{{ route('password.request') }}" class="btn btn-link text-decoration-none">Lupa Password?</a>
        </div>
    </div>
</div>
@endsection
