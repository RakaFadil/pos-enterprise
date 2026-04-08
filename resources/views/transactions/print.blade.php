<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $transaction->no_transaksi }}</title>
    <style>
        /* Desain khusus Printer Thermal 58mm */
        @page {
            margin: 0;
        }
        body {
            margin: 0;
            padding: 10px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            width: 58mm; /* Lebar kertas kasir */
            color: #000;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: bold;
        }
        .header {
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .store-name {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 2px 0;
            vertical-align: top;
        }
        .divider {
            border-bottom: 1px dashed #000;
            margin: 5px 0;
        }
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>
<body onload="window.print();">

    <div class="header text-center">
        <h1 class="store-name">POS ENTERPRISE</h1>
        <p style="margin: 3px 0;">Jl. Teknologi Asia No. 99<br>Telp: 0812-3456-7890</p>
    </div>

    <div style="margin-bottom: 10px;">
        <p style="margin: 2px 0;">Tgl : {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
        <p style="margin: 2px 0;">No  : {{ $transaction->no_transaksi }}</p>
        <p style="margin: 2px 0;">Opr : Kasir 1</p>
    </div>

    <!-- Menampilkan peringatan Batal jika transaksi void -->
    @if($transaction->trashed())
        <div class="text-center" style="border: 2px solid #000; padding: 5px; margin-bottom: 10px; font-weight: bold; font-size: 14px;">
            -- DIBATALKAN --
        </div>
    @endif

    <div class="divider"></div>

    <!-- Daftar Belanja -->
    <table>
        @foreach($transaction->details as $item)
        <tr>
            <td colspan="3" class="font-bold">{{ $item->product->name }}</td>
        </tr>
        <tr>
            <td>{{ $item->jumlah }}x</td>
            <td>{{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <!-- Total Bayar -->
    <table>
        <tr>
            <td class="font-bold">Total</td>
            <td class="text-right font-bold">Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Tunai</td>
            <td class="text-right">Rp {{ number_format($transaction->uang_bayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="text-right">Rp {{ number_format($transaction->uang_kembali, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="footer">
        <p style="margin: 2px 0;">Terima kasih atas kunjungan Anda!</p>
        <p style="margin: 2px 0;">Barang yang sudah dibeli tidak dapat ditukar.</p>
    </div>

</body>
</html>
