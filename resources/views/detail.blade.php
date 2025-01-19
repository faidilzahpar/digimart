@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <!-- Gambar Produk -->
            <div class="img-container @if($product->kategori === 'Asset desain') watermark @endif" style="max-height: 700px; overflow: hidden;">
                <img src="{{ asset($product->gambar) }}" class="card-img-top" alt="{{ $product->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover;" />
            </div>
        </div>
        <div class="col-md-6">
            <h1>{{ $product->nama_produk }}</h1>
            <h3>Rp. {{ number_format($product->harga, 2, ',', '.') }}</h3>

            <strong>Format File:</strong> {{ implode(', ', json_decode($product->format_file)) }} <br>
            {{ $product->deskripsi }}
        
            <!-- Tombol Beli, Chat, Keranjang -->
            <div class="mt-3">
                <a href="#" class="btn btn-outline-primary">Beli</a>
                <a href="{{ route('addToCart', $product->id) }}" class="btn btn-primary">+Keranjang</a>
            </div>
        </div>
    </div>
</div>
@endsection

