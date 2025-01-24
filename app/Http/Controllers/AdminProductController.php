<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProductController extends Controller
{
    public function __construct() {
        $user = Auth::user();
        
        if($user->role=='user'){
            abort(404);
        }
    }

    public function index()
    {
        $products = Product::all();
        return view('admin_products.index', compact('products'));
    }

    public function create()
    {
        return view('admin_products.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Tambahkan batasan ukuran dan tipe file
            'files' => 'nullable|array',
            'format_file' => 'nullable|array',
            'kategori' => 'required|string|max:255',
        ]);

        // Proses upload gambar
        $gambarName = time() . '_' . $request->file('gambar')->getClientOriginalName();
        $gambarPath = $request->file('gambar')->move(public_path('assets'), $gambarName);

        // Proses upload file
        $filePaths = [];
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('assets/files'), $fileName);
                $filePaths[] = 'assets/files/' . $fileName;
            }
        }

        // Simpan data ke database
        Product::create([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'gambar' => 'assets/' . $gambarName, // Path gambar
            'file' => json_encode($filePaths), // Ubah array file ke JSON
            'format_file' => json_encode($request->format_file), // Ubah array format_file ke JSON
            'kategori' => $request->kategori,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('admin_products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // Validasi input
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar bersifat opsional saat update
            'file' => 'nullable|array',
            'format_file' => 'nullable|array',
            'kategori' => 'required|string|max:255',
        ]);

        if ($request->hasFile('gambar')) {
            $fileName = time() . '_' . $request->file('gambar')->getClientOriginalName();
            $filePath = $request->file('gambar')->move(public_path('assets'), $fileName);
            $product->gambar = 'assets/' . $fileName; // Update path gambar
        }
         // Menyimpan path file jika ada file yang diupload
         if ($request->hasFile('file')) {
            $filePaths = [];
            foreach ($request->file('file') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('assets/files'), $fileName);
                $filePaths[] = 'assets/files/' . $fileName;
            }
            // Update kolom file dengan data file terbaru
            $product->file = json_encode($filePaths);
        }

        // Update informasi produk
        $product->nama_produk = $request->nama_produk;
        $product->deskripsi = $request->deskripsi;
        $product->harga = $request->harga;
        $product->format_file = $request->format_file;
        $product->kategori = $request->kategori;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        // Hapus produk
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}