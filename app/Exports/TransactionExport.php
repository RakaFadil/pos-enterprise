<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Transaction::with('details')->whereNull('deleted_at')->latest()->get();
    }

    public function headings(): array {

        return [
            'Waktu Transaksi',
            'Nomor Struk',
            'Pendapatan Kotor (Rp)',
            'Laba Bersih (Rp)',
        ];

    }

    public function map($transaction): array {
        return [
            $transaction->created_at->format('d/m/Y H:i'),
            $transaction->no_transaksi,
            $transaction->total_harga,
            // Secara cerdas menjumlahkan profit dari semua barang di dalam 1 struk
            $transaction->details->sum('profit') 
        ];
    }
}
