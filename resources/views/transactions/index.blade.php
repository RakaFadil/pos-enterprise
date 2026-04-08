@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">Riwayat Transaksi</h2>
        <!-- Bungkus kedua tombol agar rapi -->
        <div>
            <!-- Inilah Tombol Download Excel kita -->
            <a href="/transaksi/laporan/excel" class="btn" style="background-color: #10b981; margin-right: 10px;">📊 Unduh
                Laporan Excel</a>
            <a href="/transaksi/baru" class="btn">+ Transaksi Baru</a>
        </div>
    </div>

    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden;">
        <thead>
            <tr style="background-color: #f8fafc; text-align: left;">
                <th style="padding: 15px; border-bottom: 2px solid #e2e8f0;">No. Transaksi</th>
                <th style="padding: 15px; border-bottom: 2px solid #e2e8f0;">Tanggal</th>
                <th style="padding: 15px; border-bottom: 2px solid #e2e8f0;">Total</th>
                <th style="padding: 15px; border-bottom: 2px solid #e2e8f0;">Bayar</th>
                <th style="padding: 15px; border-bottom: 2px solid #e2e8f0;">Kembali</th>
                <th style="padding: 15px; border-bottom: 2px solid #e2e8f0; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
                <!-- Jika Trashed (Dibatalkan), warnai baris jadi merah muda -->
                <tr style="{{ $t->trashed() ? 'background-color: #fef2f2; opacity: 0.8;' : '' }}">
                    <td
                        style="padding: 15px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #4f46e5; {{ $t->trashed() ? 'text-decoration: line-through; color: #ef4444;' : '' }}">
                        {{ $t->no_transaksi }}
                    </td>
                    <td style="padding: 15px; border-bottom: 1px solid #e2e8f0;">
                        {{ $t->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td style="padding: 15px; border-bottom: 1px solid #e2e8f0; font-weight: bold;">
                        Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                    </td>
                    <td style="padding: 15px; border-bottom: 1px solid #e2e8f0;">
                        Rp {{ number_format($t->uang_bayar, 0, ',', '.') }}
                    </td>
                    <td style="padding: 15px; border-bottom: 1px solid #e2e8f0; color: #10b981;">
                        Rp {{ number_format($t->uang_kembali, 0, ',', '.') }}
                    </td>
                    <td style="padding: 15px; border-bottom: 1px solid #e2e8f0; text-align: center;">

                        <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                            <!-- Tombol Detail Selalu Muncul (Termasuk untuk Void) -->
                            <a href="/transaksi/{{ $t->id }}" class="btn"
                                style="background-color: #64748b; padding: 5px 10px; font-size: 12px; text-decoration: none;">🔍
                                Detail</a>

                            @if($t->trashed())
                                <!-- Jika sudah dibatalkan, hanya label alasan tanpa tombol Batal -->
                                <span
                                    style="background-color: #fee2e2; color: #b91c1c; padding: 5px 10px; border-radius: 4px; font-size: 11px; font-weight: bold;">
                                    VOID: {{ $t->void_reason }}
                                </span>
                            @else
                                <!-- Jika transaksi Normal -->
                                <form action="/transaksi/{{ $t->id }}" method="POST" id="form-batal-{{ $t->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="alasan_void" id="alasan-{{ $t->id }}">

                                    <button type="button" class="btn" onclick="mintaAlasan('{{ $t->id }}')"
                                        style="background-color: #ef4444; font-size: 11px; padding: 6px 12px; border: none; cursor: pointer;">
                                        Batal
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 30px; text-align: center; color: #64748b;">
                        Belum ada transaksi.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Logika JS Untuk memaksa pengisian alasan sebelum Submit -->
    <script>
        function mintaAlasan(id) {
            // Tampilkan kotak ketik (prompt) standar browser
            let alasan = prompt("PERINGATAN! Anda akan membatalkan kas ini.\n\nTuliskan alasan Anda membatalkan pesanan (wajib diisi):");

            // Pengecekan: Jika ditekan Cancel atau mengosongkan teks
            if (alasan == null || alasan.trim() === "") {
                alert("Proses dibatalkan. Anda wajib mengisi alasan untuk Void!");
                return false;
            }

            // Tanam teks alasan ke input ghoib HTML
            document.getElementById('alasan-' + id).value = alasan;

            // Kirim datanya ke Laravel Backend (Route Delete)
            document.getElementById('form-batal-' + id).submit();
        }
    </script>
@endsection