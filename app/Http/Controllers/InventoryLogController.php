<?php

namespace App\Http\Controllers;

use App\Models\InventoryLog;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryLogController extends Controller
{
    // 1. Menampilkan Daftar Riwayat & Form Tambah
    public function index() {
        // Ambil riwayat terbaru berserta nama produk dan nama pelakunya
        $logs = InventoryLog::with(['product', 'user'])->latest()->get();

        // Ambil semua produk untuk pilihan di Dropdown form
        $products = Product::all();

        return view('inventory.index', compact('logs', 'products'));
    }

    // 2. Menerima data Form dan mengeksekusinya
    public function store (Request $request) {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'description' => 'required|string|max:255'
        ]);

        // Gunakan DB Transaction agar jika gagal di tengah jalan, stok tidak terlanjur berubah
        DB::beginTransaction();
        try {
             // A. Tulis Jejak Pelaku ke CCTV (Log)
             $log = new InventoryLog();
             $log->product_id = $request->product_id;
             $log->user_id = Auth::id(); // Identitas Admin yang sedang login

             $log->type = $request->type;
             $log->quantity = $request->quantity;
             $log->description = $request->description;
             $log->save();
             // B. Sesuaikan Stok Fisik secara Nyata di Tabel Master Produk!

             $product = Product::find($request->product_id);

             if ($request->type === 'in') {
                $product->stock += $request->quantity; // Stok Masuk dari Supplier
             } else {
                // Cegah agar pengeluaran/buangan tidak melebihi stok yang ada
                if ($product->stock < $request->quantity) {
                    return back()->with('error', 'Stok fisik tidak cukup untuk dikeluarkan!');
                }
                $product->stock -= $request->quantity; // Buang Stok Rusak / Kedaluwarsa
             }

             $product->save();

              // C. Jika A dan B sukses, patenkan!
              DB::commit();
              return back()->with('success', 'Riwayat berhasil dicatat & stok gudang otomatis diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem:' . $e->getMessage());
        }

    }
}
