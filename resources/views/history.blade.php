@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Histori Pembelian</h1>
    <div class="row g-4">
        @foreach ($products as $product)
            <div class="col-md-4">
                <div class="card" style="height: 42 0px;">
                    <div class="img-container" style="height: 280px; overflow: hidden;">
                        <img src="{{ asset($product->gambar) }}" alt="{{ $product->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover;" />
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->nama_produk }}</h5>
                        <p class="harga">Rp. {{ number_format($product->harga, 2, ',', '.') }}</p>
                        <p>Pada {{ \Carbon\Carbon::parse($product->purchase_date)->format('d M Y') }}</p>
                        @foreach (json_decode($product->file) as $file)
                            <a href="{{ route('downloadFiles', ['id' => $product->id, 'filename' => basename($file)]) }}" class="btn btn-primary w-100">
                                Download
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection