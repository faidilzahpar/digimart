@extends('layouts.app')

@section('content')
<div class="container mt-5 d-flex justify-content-center" style="min-height: 70vh;">
    <div class="col-md-6 col-lg-5 shadow p-4 border rounded">
        <h2 class="text-center mb-4">Daftar</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Daftar</button>
            <div class="mt-3 text-center">
                <span>Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none">Masuk di sini</a></span>
            </div>
        </form>
    </div>
</div>
@endsection
