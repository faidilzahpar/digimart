@extends('layouts.admin')

@section('content')

    <h1>Daftar Produk</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Produk</a>
    

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive">
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Kategori</th>
                <th>File</th>
                <th>Format File</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->nama_produk }}</td>
                    <td>{{ $product->deskripsi }}</td>
                    <td>Rp. {{ number_format($product->harga, 0, ',', '.') }}</td>
                    <td>{{ $product->kategori }}</td>
                    <td>
                        @if($product->file)
                            @foreach(json_decode($product->file) as $file)
                                <a href="{{ asset($file) }}" target="_blank"
                                style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block; max-width: 100px;">
                                {{ basename($file) }}</a><br>
                            @endforeach
                        @else
                            Tidak ada file yang diupload.
                        @endif
                    </td>
                    <td>{{ $product->format_file }}</td>
                    <td><img src="{{ asset($product->gambar) }}" alt="{{ $product->nama_produk }}" width="100"></td>
                    <td>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary my-1">Edit</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger my-1" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</button>
                        </form>
                    </td>   
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>

@endsection