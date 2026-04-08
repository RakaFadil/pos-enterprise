<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index() {
        // 1. Hitung total uang yang masuk (kotor)
        $total_pendapatan = Transaction::sum('total_harga');

        // 1.B Hitung Laba Penjualan POS (Kotor)
        $laba_kotor_pos = \App\Models\TransactionDetail::whereHas('transaction', function ($query) {
            $query->whereNull('deleted_at'); 
        })->sum('profit');

        // 1.C. Ekstrak data operasional dari Buku Kas
        $operasional_masuk = \App\Models\CashFlow::where('type', 'in')->sum('amount');
        $operasional_keluar = \App\Models\CashFlow::where('type', 'out')->sum('amount');

         // 1.D. RUMUS NET INCOME (Laba Bersih Sejati)
        $total_profit = $laba_kotor_pos + $operasional_masuk - $operasional_keluar;

        // 2. Hitung jumlah pelanggan (transaksi)
        $jumlahTransaksi = Transaction::count();

        // 3. Info Master Data
        $totalProduk = Product::count();
        $totalKategori = \App\Models\Category::count();

        // 4. Fitur Bonus: Memantau 5 Produk yang stoknya paling tipis/habis!
        $stokTipis = Product::where('stock', '<', 50)->orderBy('stock', 'asc')->get();

        // 5. Data Grafik Pendapatan 30 Hari (Perbulan)
        $grafik_labels = [];
        $grafik_data = [];
         // Tarik massal semua transaksi 29 hari lalu + hari ini = 30 HARI
        $transaksi_bulanan = Transaction::whereNull('deleted_at')->where('created_at', '>=', Carbon::today()->subDays(29))->get();

        // Peras datanya hari demi hari (dari tanggal terlama -> hari ini)
        for ($i = 29; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i)->format('Y-m-d');
            $grafik_labels[] = Carbon::today()->subDays($i)->format('d M');

            $total_harian = $transaksi_bulanan->whereBetween('created_at', [
                $tanggal . ' 00:00:00',
                $tanggal . ' 23:59:59'
            ])->sum('total_harga');
            
            $grafik_data[] = $total_harian;
        }


        return view('dashboard.index', compact(
            'total_pendapatan',
            'total_profit',
            'jumlahTransaksi',
            'totalProduk',
            'totalKategori',
            'stokTipis',
            // Variabel ghoib yang akan kita lempar ke Visual (WAJIB DIDAFTARKAN):
            'grafik_labels', 
            'grafik_data' 
        ));
    }
}
