<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{

    public function index() {
        // Ambil data transaksi terbaru di urutan teratas
        // withTrashed() artinya minta database menyorot data yang sudah di-SoftDelete
        $transactions = Transaction::withTrashed()->latest()->get();
        return view('transactions.index', compact('transactions'));
    }

    public function create() {
        $products = Product::where('stock', '>', 0)->get();
        return view('transactions.create', compact('products'));
    }

    public function store(Request $request) {

        $request->validate([
            'uang_bayar' => 'required|numeric|min:' . $request->total_harga,
            'product_id' => 'required|array',
            'product_id.*' => 'exists:products,id'
        ], [
            'uang_bayar.min' => 'Uang bayar kurang dari total belanja!',
            'product_id.required' => 'Keranjang masih kosong!'
        ]);

        DB::beginTransaction();

        try {
            // A. Simpan Transaksi Unduk (Struk)
            $transaksi = new Transaction();
            $transaksi->no_transaksi = 'TRX-' . date('Ymdhis') . rand(100, 999);
            $transaksi->total_harga = $request->total_harga;
            $transaksi->uang_bayar = $request->uang_bayar;
            $transaksi->uang_kembali = $request->uang_kembali ?? ($request->uang_bayar - $request->total_harga);
            // $transaksi->tanggal_transaksi otomatis diisi mysql (useCurrent)
            $transaksi->save();

            // PENTING: Menangkap array data yang dikirim dari input tabel HTML
            $product_ids = $request->product_id;
            $hargas = $request->harga_satuan;
            $qtys = $request->qty;

            // B. Looping setiap barang yang ada di keranjang
            for ($i = 0; $i < count($product_ids); $i++) {
                $pid = $product_ids[$i];
                $qty = $qtys[$i];
                $harga = $hargas[$i];
                $subtotal = $qty * $harga;

                // Tarik data asli product dari Database (Termasuk harga modal rahasianya)
                $product = Product::find($pid);

                // C. Simpan Detail Transaksi beserta Kalkulasi Laba
                $detail = new TransactionDetail();
                $detail->transaction_id = $transaksi->id;
                $detail->product_id = $pid;
                $detail->jumlah = $qty;
                $detail->harga_satuan = $harga;
                $detail->subtotal = $subtotal;
                
                // SISTEM PROFIT OTOMATIS: (Harga Jual Kasir - Harga Modal Database) * Qty Pembelian
                $detail->harga_modal = $product->harga_modal;
                $detail->profit = ($harga - $product->harga_modal) * $qty;

                $detail->save();

                // D. Kurangi Stok Barang yang dibeli
                $product->stock = $product->stock - $qty;
                $product->save();

            }

            // Jika semua di atas sukses, patenkan ke database!
            DB::commit();

            // Kembali ke halaman kasir dengan pesan sukses
            return redirect('/transaksi')->with('success','Transaksi' . $transaksi->no_transaksi . 'Berhasil disimpan!');

        } catch (\Exception $e) {
            // Jika terjadi error di tengah jalan, batalkan semuanya
            DB::rollBack();
            return back()->withError('Terjadi kesalahan sistem: ' . $e->getMessage());
        }

    }

    public function show($id) {
        // withTrashed() diperlukan agar kita tetap bisa melihat struk dari transaksi yang dibatalkan
        $transaction = Transaction::withTrashed()->with('details.product')->findOrFail($id);

        return view('transactions.show', compact('transaction'));
    }

    // Fungsi Khusus Cetak Struk Printer Thermal
    public function print($id) {
        $transaction = Transaction::withTrashed()->with('details.product')->findOrFail($id);

        return view('transactions.print', compact('transaction'));
    }

    public function destroy(Request $request, $id)
    {
        // PENTING: Gunakan DB Transaction karena kita mengubah 2 tabel berbeda (Produk & Transaksi)
        DB::beginTransaction();

        try {
            // Tarik transaksi BERSERTA detail barangnya
            $transaction = Transaction::with('details')->findOrFail($id);

            // 1. KEMBALIKAN STOK BARANG DULU
            foreach ($transaction->details as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->stock = $product->stock + $item->jumlah; // Tambah kembali stoknya
                    $product->save();
                }
            }

            // 2. SIMPAN ALASAN & HAPUS (Soft Delete)
            $transaction->void_reason = $request->alasan_void;

            $transaction->save();
            $transaction->delete();

            DB::commit();
            return redirect('/transaksi')->with('success', 'Transaksi dibatalkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan transaksi!');
        }
    }

    public function exportExcel() 
    {
        return Excel::download(new TransactionExport, 'Laporan_Keuangan_POS.xlsx');
    }
}
