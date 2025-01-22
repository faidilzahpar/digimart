<?php

namespace App\Http\Controllers;

use App\Models\Product; // pastikan model Product sudah dibuat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('index', compact('products'));
    }

    // Search
    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('nama_produk', 'LIKE', '%' . $query . '%')
                           ->orWhere('deskripsi', 'LIKE', '%' . $query . '%')
                           ->get();
        return view('search', compact('products'));
    }

    public function detail($id)
    {
        $product = Product::findOrFail($id); // Mencari produk berdasarkan ID
        return view('detail', compact('product')); // Mengirim data produk ke view
    }

    // Kategori
    public function kategori($kategori)
    {
        // Ambil produk berdasarkan kategori
        $products = DB::table('products')->where('kategori', $kategori)->get();

        // Pastikan kategori valid
        if ($products->isEmpty()) {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan.');
        }

        // Kirim data ke view
        return view('kategori', [
            'kategori' => ucwords($kategori), 
            'products' => $products,
        ]);
    }


    public function addToCart($id)
    {
        // Periksa apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Silakan login untuk menambahkan produk ke keranjang.');
        }

        // Cari produk berdasarkan ID
        $product = Product::find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan!');
        }

        // Ambil keranjang dari session atau buat array kosong jika belum ada
        $cart = session()->get('cart', []);

        // Jika produk sudah ada di keranjang, tambahkan jumlahnya
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Jika produk belum ada di keranjang, tambahkan dengan quantity 1
            $cart[$id] = [
                'nama_produk' => $product->nama_produk,
                'harga' => $product->harga,
                'gambar' => $product->gambar,
                'quantity' => 1, // Tambahkan key quantity
            ];
        }
        // Simpan kembali keranjang ke session
        session()->put('cart', $cart);
        // Tambahkan pesan flash untuk sukses
        session()->flash('success', 'Produk berhasil ditambahkan ke keranjang.');
        // Arahkan kembali ke halaman sebelumnya
        return redirect()->back();
    }

    public function checkout()
    {
        // Cek apakah ada keranjang belanja
        if (!session()->has('cart') || count(session('cart')) == 0) {
            return redirect()->route('index')->with('error', 'Keranjang kosong!');
        }

        // Kirimkan data keranjang ke view checkout
        return view('checkout');
    }
    public function processCheckout(Request $request)
    {
        $selectedProducts = $request->input('selected_products');

        if (!$selectedProducts) {
            return redirect()->route('checkout')->with('error', 'Tidak ada produk yang dipilih!');
        }

        $cart = session('cart');
        $checkoutProducts = [];

        // Simpan status pembelian produk yang dipilih
        foreach ($selectedProducts as $productId) {
            if (isset($cart[$productId])) {
                $checkoutProducts[$productId] = $cart[$productId];

                // Tandai produk sebagai sudah dibeli
                session(['purchased_product_' . $productId => true]);
                session(['purchase_date_' . $productId => now()->format('Y-m-d H:i:s')]);
            }
        }

        // Hapus produk yang dibeli dari keranjang
        foreach ($checkoutProducts as $productId => $product) {
            unset($cart[$productId]);
        }

        // Perbarui sesi keranjang
        session(['cart' => $cart]);

        return redirect()->route('history')->with('success', 'Pembayaran berhasil! Produk yang dibeli dapat dilihat di histori.');
    }


    public function removeFromCart($id)
    {
        // Cek apakah ada produk dalam keranjang
        if (session()->has('cart')) {
            $cart = session('cart');
            
            // Hapus produk dari keranjang
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
                return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang');
            }
        }
        
        return redirect()->back()->with('error', 'Produk tidak ditemukan dalam keranjang');
    }

    public function processPurchase($id)
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Silakan login untuk membeli produk.');
        }
        $product = Product::findOrFail($id);
        // Set status produk sebagai sudah dibeli dalam session
        session(['purchased_product_' . $id => true]);
        session(['purchase_date_' . $id => now()->format('Y-m-d H:i:s')]);
        return redirect()->route('detail', $id)->with('success', 'Produk berhasil dibeli.');
    }

    public function downloadFiles($id, $filename)
    {
        $product = Product::findOrFail($id);
        // Periksa jika pengguna sudah membeli produk (misalnya melalui session)
        if (!session('purchased_product_' . $id)) {
            return redirect()->route('detail', $id)->with('error', 'Anda harus membeli produk terlebih dahulu.');
        }
        // Cari file di folder assets/files
        $filePath = public_path("assets/files/{$filename}");
        // Periksa apakah file ada
        if (!file_exists($filePath)) {
            return redirect()->route('detail', $id)->with('error', 'File tidak ditemukan.');
        }
        session()->flash('success', 'Unduhan telah dimulai.');
        // Kirimkan file untuk diunduh
        return response()->download($filePath);
    }

    public function purchaseHistory()
    {
        $purchasedProductIds = array_map(function ($key) {
            return str_replace('purchased_product_', '', $key);
        }, array_filter(array_keys(session()->all()), function ($key) {
            return str_starts_with($key, 'purchased_product_');
        }));

        $products = Product::whereIn('id', $purchasedProductIds)->get();

        // Tambahkan tanggal pembelian untuk setiap produk
        $productsWithDates = $products->map(function ($product) {
            $product->purchase_date = session('purchase_date_' . $product->id);
            return $product;
        })->sortByDesc('purchase_date');

        return view('history', ['products' => $productsWithDates]);
    }

}