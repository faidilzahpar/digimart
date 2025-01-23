<nav class="navbar navbar-expand-md bg-body-tertiary sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="https://github.com/faidilzahpar/CRUD-laravel/blob/main/assets/LogofullDigiMart.png?raw=true" target="blank"> 
        <img src="https://github.com/faidilzahpar/CRUD-laravel/blob/main/assets/LogoDigiMart200px.png?raw=true" alt="Logo" width="40" height="40" class="d-inline-block align-text-top"> 
      </a>
      <div class="col-md-5 col-lg-7 mx-auto">
        <form class="d-flex" role="search" method="GET" action="{{ route('search') }}">
            <input class="form-control me-2" type="search" name="query" placeholder="Cari disini" aria-label="Search" />
            <button class="btn btn-outline-primary" type="submit"><i class='bx bx-search'></i></button>
        </form>
      </div>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse ms-4" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link {{ (request()->is('/')) ? 'active' : '' }}" href="/">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="/#produk">Produk</a></li>
          <li class="nav-item dropdown">
            <a class="class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ (request()->is('kategori/*')) ? 'active' : '' }} dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Kategori</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item {{ (request()->is('kategori/E-book')) ? 'active' : '' }}" href="{{ route('kategori', 'E-book') }}">E-book</a></li>
              <li><a class="dropdown-item {{ (request()->is('kategori/Kelas online')) ? 'active' : '' }}" href="{{ route('kategori', 'Kelas online') }}">Kelas online</a></li>
              <li><a class="dropdown-item {{ (request()->is('kategori/Template desain')) ? 'active' : '' }}" href="{{ route('kategori', 'Template desain') }}">Template desain</a></li>
              <li><a class="dropdown-item {{ (request()->is('kategori/Asset desain')) ? 'active' : '' }}" href="{{ route('kategori', 'Asset desain') }}">Asset desain</a></li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link {{ (request()->is('history')) ? 'active' : '' }}" href="{{ route('history') }}">Histori</a></li>
          @if(Auth::check() && Auth::user()->role === 'admin')
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.index') }}">Admin</a></li>
          @endif
        </ul>
      </div>
      <div class="auth w-100 d-flex justify-content-end">
        @guest
            <span class="garis border-start mx-2" style="height: 30px;"></span>
            <a href="{{ route('login') }}" class="btn btn-outline-primary mx-1">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-primary mx-1">Daftar</a>
        @else
            <a href="{{ route('profile') }}" class="text-decoration-none text-dark fw-semibold">
              <img src="{{ asset(Auth::user()->image) }}" alt="Profile Image" class="profile-image img-fluid rounded-circle border border-white me-1" style="width: 40px; height: 40px; object-fit: cover; box-shadow: 0 0 0 3px #0d6efd;">
            </a>
        @endguest
    </div>
    
    
    </div>
  </nav>
  @guest
  @else
  {{-- Keranjang Dropdown --}}
  <div class="dropdown position-fixed z-3">
    <button class="btn btn-primary dropdown-toggle rounded-pill position-fixed bottom-0 end-0 m-4 fs-3 lh-lg shadow-sm" type="button" id="cartDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class='bx bxs-cart-alt'></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="cartDropdown" style="min-width: 300px;">
        @if (session('cart') && count(session('cart')) > 0)
            @foreach (session('cart') as $id => $item)
                <li class="dropdown-item d-flex gap-2">
                    <img src="{{ asset($item['gambar']) }}" alt="{{ $item['nama_produk'] }}" style="width: 50px; height: 50px; object-fit: cover;">
                    <div>
                        <p class="mb-0">{{ $item['nama_produk'] }}</p>
                        <small>Rp. {{ number_format($item['harga'], 2, ',', '.') }}</small>
                    </div>
                    <form action="{{ route('removeFromCart', $id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn-close"></button>
                  </form>
                </li>
            @endforeach
            <li><hr class="dropdown-divider"></li>
            <li class="dropdown-item text-center">
                <a href="{{ route('checkout') }}" class="btn btn-primary btn-sm">Checkout</a>
            </li>
        @else
            <li class="dropdown-item text-center">Keranjang kosong</li>
        @endif
    </ul> 
  </div>
  @endguest
