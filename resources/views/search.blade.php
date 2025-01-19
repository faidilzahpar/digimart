@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Hasil Pencarian untuk: {{ request()->input('query') }}</h1>

    @if ($products->isEmpty())
        <p>Tidak ada produk yang ditemukan.</p>
    @else
        <div class="row g-4 my-4">
            @foreach ($products as $product)
                <div class="col-md-4">
                    <div class="card" style="height: 480px;">
                        <div class="img-container @if($product->kategori === 'Asset desain') watermark @endif" style="height: 280px; overflow: hidden;">
                            <img src="{{ asset($product->gambar) }}" class="card-img-top" alt="{{ $product->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover;" />
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->nama_produk }}</h5>
                            <p class="card-text" style="overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2; max-height: 3em; line-height: 1.5em; margin: 0;">
                                {{ $product->deskripsi }}
                            </p>
                            <h5 class="harga">Rp. {{ number_format($product->harga, 2, ',', '.') }}</h5>
                            <a href="{{ route('detail', $product->id) }}" class="btn btn-primary mt-2">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
