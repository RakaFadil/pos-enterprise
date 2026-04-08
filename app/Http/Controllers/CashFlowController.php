<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashFlowController extends Controller
{
    // 1. Tampilkan Buku Kas dan Hitung Saldo
    public function index () {
        $cash_flows = CashFlow::with('user')->latest()->get();
        // Tarik Total Uang Fisik dari Cashier (POS)
        $uang_masuk_pos = \App\Models\Transaction::whereNull('deleted_at')->sum('total_harga');
        // Hitung Pergerakan Manual
        $pemasukan_manual = CashFlow::where('type', 'in')->sum('amount');
        $pengeluaran_manual = CashFlow::where('type', 'out')->sum('amount');
        // Total Kas (Pemasukan Manual) HANYA untuk tampilan label saja
        $pemasukan = $pemasukan_manual;
        $pengeluaran = $pengeluaran_manual;
        // RUMUS UANG BRANKAS SEJATI (Uang Laci + Uang Masuk Manual - Uang Keluar)
        $total_saldo = $uang_masuk_pos + $pemasukan_manual - $pengeluaran_manual;
        return view('cash_flow.index', compact('cash_flows', 'total_saldo', 'pemasukan', 'pengeluaran'));
    }

    // 2. Simpan Catatan Kas Baru ke Brankas Database
    public function store(Request $request) {
        $request->validate([
            'type' => 'required|in:in,out',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255'
        ]);

        $kas = new CashFlow();
        $kas->user_id = Auth::id(); // Otomatis mengunci nama Admin pencatat
        $kas->type = $request->type;
        $kas->amount = $request->amount;
        $kas->description = $request->description;
        $kas->save();

        return back()->with('success', 'Pergerakan Kas berhasil dicatat saku!');
    }
}
