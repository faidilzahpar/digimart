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
            'kategori' => ucfirst($kategori), // Ubah jadi huruf kapital untuk judul
            'products' => $products,
        ]);
    }


    public function addToCart($id)
{
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

        session()->flash('success', 'Produk berhasil ditambahkan ke keranjang');

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

        // Ambil detail produk yang dipilih
        foreach ($selectedProducts as $productId) {
            if (isset($cart[$productId])) {
                $checkoutProducts[$productId] = $cart[$productId];
            }
        }

        // Simpan produk yang dipilih ke sesi checkout (opsional)
        session()->put('checkout_products', $checkoutProducts);

        // Arahkan ke halaman pembayaran atau lanjutkan proses
        return redirect()->route('payment')->with('success', 'Produk berhasil dipilih untuk checkout!');
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

}