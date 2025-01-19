@extends('layouts.app')

@section('content')
    <div class="container mt-5" style="min-height: 68vh;">
        <h1 class="text-center">Checkout</h1>

        @if(session('cart') && count(session('cart')) > 0)
            <form id="checkoutForm" action="{{ route('processCheckout') }}" method="POST">
                @csrf
                <div class="cart-items">
                    <div class="mb-3">
                        <input type="checkbox" id="selectAll" class="form-check-input">
                        <label for="selectAll" class="form-check-label"><strong>Pilih Semua</strong></label>
                    </div>
                    @php
                        $totalHarga = 0; // Variabel untuk menyimpan total harga
                    @endphp
                    @foreach(session('cart') as $id => $item)
                        <div class="cart-item d-flex gap-2 align-items-center">
                            <input 
                                type="checkbox" 
                                name="selected_products[]" 
                                value="{{ $id }}" 
                                class="form-check-input product-checkbox"
                                data-harga="{{ $item['harga'] * $item['quantity'] }}">
                            <img src="{{ asset($item['gambar']) }}" alt="{{ $item['nama_produk'] }}" style="width: 50px; height: 50px; object-fit: cover;">
                            <div class="mb-2">
                                <p class="mb-0">{{ $item['nama_produk'] }}</p>
                                <small>Rp. {{ number_format($item['harga'], 2, ',', '.') }}</small>
                                @php
                                    // Hitung total harga
                                    $totalHarga += $item['harga'] * $item['quantity'];
                                @endphp
                            </div>
                        </div>
                    @endforeach
                </div>
                <hr>
                <div class="text-center">
                    <p><strong>Total Harga: <span id="totalHarga">{{ number_format($totalHarga, 2, ',', '.') }}</span></strong></p>
                    <button type="submit" class="btn btn-primary">Lanjutkan Pembayaran</button>
                </div>
            </form>
        @else
            <p>Keranjang Anda kosong.</p>
        @endif
    </div>
@endsection
