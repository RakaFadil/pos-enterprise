@extends('layouts.app')

@section('content')
    <!-- BARIS ATAS: KARTU SALDO -->
    <div style="display: flex; gap: 24px; margin-bottom: 24px;">
        <!-- Saldo Akhir -->
        <div
            style="flex: 1; background: linear-gradient(135deg, #10b981, #059669); padding: 20px; border-radius: 12px; color: white;">
            <p style="margin: 0; font-size: 14px; opacity: 0.9;">Total Saldo Brankas Saat Ini</p>
            <h2 style="margin: 5px 0 0 0; font-size: 28px;">Rp {{ number_format($total_saldo, 0, ',', '.') }}</h2>
        </div>
        <!-- Pemasukan -->
        <div
            style="flex: 1; background: white; padding: 20px; border-radius: 12px; border-left: 5px solid #3b82f6; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <p style="margin: 0; font-size: 14px; color: #64748b;">Total Kas Masuk</p>
            <h3 style="margin: 5px 0 0 0; font-size: 22px; color: #1e293b;">Rp {{ number_format($pemasukan, 0, ',', '.') }}
            </h3>
        </div>
        <!-- Pengeluaran -->
        <div
            style="flex: 1; background: white; padding: 20px; border-radius: 12px; border-left: 5px solid #ef4444; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <p style="margin: 0; font-size: 14px; color: #64748b;">Total Kas Keluar</p>
            <h3 style="margin: 5px 0 0 0; font-size: 22px; color: #1e293b;">Rp
                {{ number_format($pengeluaran, 0, ',', '.') }}</h3>
        </div>
    </div>

    <div style="display: flex; gap: 24px;">
        <!-- AREA KIRI: FORM PENCATATAN -->
        <div
            style="flex: 3.5; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-height: 400px;">
            <h3 style="margin-top: 0; color: #1e293b;">Catat Arus Kas Baru</h3>

            <form action="/buku-kas" method="POST">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 500; font-size: 14px;">Jenis Pergerakan Uang:</label>
                    <select name="type" required
                        style="width:100%; padding:8px; border: 1px solid #cbd5e1; border-radius:5px;">
                        <option value="in">🔵 UANG MASUK (Setoran Modal / Lainnya)</option>
                        <option value="out">🔴 UANG KELUAR (Biaya Operasional, Prive)</option>
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 500; font-size: 14px;">Nominal (Rp):</label>
                    <input type="number" name="amount" min="1" required
                        style="width:100%; padding:8px; border: 1px solid #cbd5e1; border-radius:5px;"
                        placeholder="Contoh: 150000">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-weight: 500; font-size: 14px;">Keterangan / Alasan:</label>
                    <input type="text" name="description" required
                        style="width:100%; padding:8px; border: 1px solid #cbd5e1; border-radius:5px;"
                        placeholder="Contoh: Bayar Biaya Sampah">
                </div>

                <button type="submit" class="btn" style="width: 100%; justify-content: center;">
                    💾 Catat ke Buku Kas
                </button>
            </form>
        </div>

        <!-- AREA KANAN: TABEL RIWAYAT -->
        <div
            style="flex: 6.5; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin-top: 0; color: #1e293b;">Riwayat Cetakan Brankas</h3>

            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background-color: #f8fafc;">
                        <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Tanggal</th>
                        <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Tipe</th>
                        <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Deskripsi</th>
                        <th style="padding: 12px; border-bottom: 2px solid #e2e8f0; text-align: right;">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cash_flows as $kas)
                        <tr>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 13px; color: #64748b;">
                                {{ $kas->created_at->format('d/m/Y - H:i') }}
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                                @if($kas->type == 'in')
                                    <span style="color: #3b82f6; font-weight: bold;">[IN] Masuk</span>
                                @else
                                    <span style="color: #ef4444; font-weight: bold;">[OUT] Keluar</span>
                                @endif
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 13px;">
                                {{ $kas->description }} <br>
                                <small style="color: #94a3b8;">Oleh: {{ $kas->user->name ?? 'Anonim' }}</small>
                            </td>
                            <td
                                style="padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: right; font-weight: bold; color: {{ $kas->type == 'in' ? '#3b82f6' : '#ef4444' }};">
                                {{ $kas->type == 'in' ? '+' : '-' }} Rp {{ number_format($kas->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 30px; color: #94a3b8;">
                                Belum ada pergerakan arus kas manual.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection