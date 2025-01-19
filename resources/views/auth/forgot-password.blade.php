@extends('layouts.app')

@section('content')
<div class="container mt-5 d-flex justify-content-center" style="min-height: 68vh;">
    <div class="col-md-6 col-lg-5 shadow p-4 border rounded">
        <h2 class="text-center">Lupa Password</h2>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
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

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <!-- Jika pengguna sudah login, email diisi otomatis dan tidak bisa diubah -->
            <div class="mb-3">
                <label for="email" class="form-label mt-3">Email</label>
                @if (Auth::check())
                    <!-- Pengguna sudah login, email diisi otomatis dan tidak bisa diubah -->
                    <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" readonly>
                @else
                    <!-- Pengguna belum login, email bisa diisi manual -->
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                @endif
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-2">Kirim Link Reset</button>
        </form>
    </div>
</div>
@endsection
