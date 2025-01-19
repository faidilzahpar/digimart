@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Tambah Produk</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nama_produk" class="form-label">Nama Produk</label>
            <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" class="form-control" id="harga" name="harga" required>
        </div>
        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar</label>
            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
            {{-- <small class="form-text text-muted">Gambar akan disimpan di folder public/assets.</small> --}}
        </div>
        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select class="form-control" id="kategori" name="kategori" required>
                <option value="" disabled selected>Pilih Kategori</option>
                <option value="Template desain">Template desain</option>
                <option value="Kelas online">Kelas online</option>
                <option value="Asset desain">Asset desain</option>
                <option value="E-book">E-book</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="format_file" class="form-label">Format File</label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="png" class="form-check-input"> PNG
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="jpeg" class="form-check-input"> JPEG
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="jpg" class="form-check-input"> JPG
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="mp4" class="form-check-input"> MP4
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="svg" class="form-check-input"> SVG
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="ai" class="form-check-input"> AI
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="psd" class="form-check-input"> PSD
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="pptx" class="form-check-input"> PPTX
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="cdr" class="form-check-input"> CDR
            </label><br>
            <label class="form-check-label">
                <input type="checkbox" name="format_file[]" value="pdf" class="form-check-input"> PDF
            </label>
        </div>
        <button type="submit" class="btn btn-primary mb-4">Simpan</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary mb-4">Kembali</a>
    </form>
</div>
@endsection