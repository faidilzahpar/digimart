@extends('layouts.app')

@section('content')
<div class="container mt-5 d-flex justify-content-center" style="min-height: 70vh;">
    <div class="col-md-6 col-lg-5 shadow p-4 border rounded">
        <h2 class="text-center mb-4">Masuk</h2>
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
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Masuk</button>
            <div class="mt-3 text-center">
                <span>Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none">Daftar di sini</a></span>
            </div>
        </form>
    </div>
</div>
@endsection
