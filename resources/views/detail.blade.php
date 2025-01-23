@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <!-- Gambar Produk -->
            <div class="img-container shadow rounded border @if($product->kategori === 'Asset desain') watermark @endif" style="max-height: 700px; overflow: hidden;">
                <img src="{{ asset($product->gambar) }}" class="card-img-top" alt="{{ $product->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover;" />
            </div>
        </div>
        <div class="col-md-6">
            <h1>{{ $product->nama_produk }}</h1>
            <h3>Rp. {{ number_format($product->harga, 2, ',', '.') }}</h3>

            <strong>Format File:</strong> {{ implode(', ', json_decode($product->format_file)) }} <br>
            {{ $product->deskripsi }}
        
            <!-- Tombol Beli & Keranjang -->
            <div class="mt-3 d-flex gap-1">
                @if(session('purchased_product_' . $product->id))
                    <!-- Tombol Download jika produk sudah dibeli -->
                    @foreach (json_decode($product->file) as $file)
                        <a href="{{ route('downloadFiles', ['id' => $product->id, 'filename' => basename($file)]) }}" class="btn btn-primary">
                            Download
                        </a>
                    @endforeach
                @else
                <!-- Tombol Beli dan Keranjang jika produk belum dibeli -->
                <form action="{{ route('processPurchase', $product->id) }}" method="POST" onsubmit="return confirmPurchase();">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">Beli</button>
                </form>
                <form action="{{ route('addToCart', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">+Keranjang</button>
                </form>
                @endif
            </div>            
        </div>
    </div>
</div>
@endsection

