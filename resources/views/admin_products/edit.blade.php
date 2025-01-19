@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Produk</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nama_produk" class="form-label">Nama Produk</label>
            <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="{{ $product->nama_produk }}" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>{{ $product->deskripsi }}</textarea>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" class="form-control" id="harga" name="harga" value="{{ $product->harga }}" required>
        </div>
        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar (Kosongkan jika tidak ingin mengubah)</label>
            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
        </div>
        <div class="mb-3">
            <img src="{{ asset($product->gambar) }}" alt="{{ $product->nama_produk }}" width="100">
        </div>
        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select class="form-control" id="kategori" name="kategori" required>
                <option value="" disabled>Pilih Kategori</option>
                <option value="Template desain" {{ $product->kategori == 'Template desain' ? 'selected' : '' }}>Template desain</option>
                <option value="Kelas online" {{ $product->kategori == 'Kelas online' ? 'selected' : '' }}>Kelas online</option>
                <option value="Asset desain" {{ $product->kategori == 'Asset desain' ? 'selected' : '' }}>Asset desain</option>
                <option value="E-book" {{ $product->kategori == 'E-book' ? 'selected' : '' }}>E-book</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="format_file" class="form-label">Format File</label><br>
            @php
                // Mengonversi format_file string menjadi array
                $formatFileArray = explode(',', $product->format_file);
            @endphp
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="png" class="form-check-input" {{ in_array('png', $formatFileArray) ? 'checked' : '' }}> PNG
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="jpeg" class="form-check-input" {{ in_array('jpeg', $formatFileArray) ? 'checked' : '' }}> JPEG
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="jpg" class="form-check-input" {{ in_array('jpg', $formatFileArray) ? 'checked' : '' }}> JPG
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="mp4" class="form-check-input" {{ in_array('mp4', $formatFileArray) ? 'checked' : '' }}> MP4
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="svg" class="form-check-input" {{ in_array('svg', $formatFileArray) ? 'checked' : '' }}> SVG
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="ai" class="form-check-input" {{ in_array('ai', $formatFileArray) ? 'checked' : '' }}> AI
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="psd" class="form-check-input" {{ in_array('psd', $formatFileArray) ? 'checked' : '' }}> PSD
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="pptx" class="form-check-input" {{ in_array('pptx', $formatFileArray) ? 'checked' : '' }}> PPTX
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="cdr" class="form-check-input" {{ in_array('cdr', $formatFileArray) ? 'checked' : '' }}> CDR
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="pdf" class="form-check-input" {{ in_array('pdf', $formatFileArray) ? 'checked' : '' }}> PDF
            </label>
        </div>
        <button type="submit" class="btn btn-primary mb-4">Update</button>
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary mb-4">Kembali</a>
    </form>
</div>
@endsection