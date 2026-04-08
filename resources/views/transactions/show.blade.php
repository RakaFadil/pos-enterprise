@extends('layouts.app')

@section('content')
    <div class="no-print" style="margin-bottom: 20px; display: flex; justify-content: space-between;">
        <a href="/transaksi" class="btn" style="background-color: #64748b;">⬅ Kembali</a>
        <button onclick="window.print()" class="btn" style="background-color: #10b981;">Print Struk 🖨️</button>
    </div>

    <div class="receipt-card">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="margin: 0; text-transform: uppercase;">TOKO POS RAKA</h2>
            <p style="margin: 5px 0; font-size: 14px;">Jl. Belajar Laravel No. 1, Indonesia</p>
            <p style="margin: 0; font-size: 12px;">Telp: 0812-3456-7890</p>
        </div>

        <div
            style="border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc; padding: 10px 0; margin-bottom: 15px; font-size: 14px;">
            <div style="display: flex; justify-content: space-between;">
                <span>No: {{ $transaction->no_transaksi }}</span>
                <span>Kasir: Admin</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Tgl: {{ $transaction->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; font-size: 14px; margin-bottom: 15px;">
            @foreach($transaction->details as $detail)
                <tr>
                    <td colspan="3" style="padding-top: 5px;">{{ $detail->product->name }}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 5px; color: #666;">{{ $detail->jumlah }} x
                        {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                    <td style="text-align: right; vertical-align: bottom;">Rp
                        {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>

        <div style="border-top: 1px dashed #ccc; padding-top: 10px; font-size: 14px;">
            <div
                style="display: flex; justify-content: space-between; font-weight: bold; font-size: 16px; margin-bottom: 5px;">
                <span>TOTAL</span>
                <span>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                <span>Bayar</span>
                <span>Rp {{ number_format($transaction->uang_bayar, 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Kembali</span>
                <span>Rp {{ number_format($transaction->uang_kembali, 0, ',', '.') }}</span>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px; font-size: 12px;">
            <p style="margin: 0;">Terima Kasih Atas Kunjungan Anda!</p>
            <p style="margin: 5px 0;">Barang yang sudah dibeli</p>
            <p style="margin: 0;">tidak dapat ditukar/dikembalikan.</p>
        </div>
    </div>

    <style>
        /* Desain Kartu Struk di Layar */
        .receipt-card {
            background: white;
            width: 400px;
            margin: 0 auto;
            padding: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        /* KEAJAIBAN PRINT: Hanya dieksekusi saat tombol Cetak ditekan */
        @media print {

            /* Sembunyikan semua elemen kecuali struk */
            body * {
                visibility: hidden;
                background: white !important;
            }

            .receipt-card,
            .receipt-card * {
                visibility: visible;
            }

            /* Atur agar struk berada di pojok kiri atas kertas printer */
            .receipt-card {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                /* Sesuai lebar kertas printer thermal */
                box-shadow: none;
                border: none;
                padding: 0;
            }

            /* Sembunyikan Sidebar & Tombol Kembali */
            .sidebar,
            .no-print,
            .sidebar-brand,
            .sidebar-menu {
                display: none !important;
            }

            .main-content {
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
@endsection