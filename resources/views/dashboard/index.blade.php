@extends('layouts.app')

@section('content')
    <div style="margin-bottom: 30px;">
        <h2 style="margin: 0; color: #1e293b;">Dashboard Ringkasan</h2>
        <p style="color: #64748b; margin-top: 5px;">Selamat datang kembali, Admin! Berikut performa toko Anda.</p>
    </div>

    <!-- CSS Grid untuk Kartu Metrik (Terbagi 4 kolom sama besar) -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px;">

        <!-- Kartu 1: Total Pendapatan -->
        <div
            style="background: linear-gradient(135deg, #4f46e5, #3b82f6); padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.5);">
            <p style="margin: 0 0 10px 0; font-size: 14px; opacity: 0.9;">Total Pendapatan Kasir</p>
            <h3 style="margin: 0; font-size: 24px;">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</h3>
        </div>

        <div
            style="background: linear-gradient(135deg, #4f46e5, #3b82f6); padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.5);">
            <p style="margin: 0 0 10px 0; font-size: 14px; opacity: 0.9;">Total Laba Bersih (Profit)</p>
            <h3 style="margin: 0; font-size: 24px;">Rp {{ number_format($total_profit ?? 0, 0, ',', '.') }}</h3>
            <p style="margin: 0 0 10px 0; font-size: 14px; opacity: 0.9;">Berdasar dari transaksi sukses</p>
        </div>


        <!-- Kartu 2: Jumlah Transaksi -->
        <div
            style="background: linear-gradient(135deg, #10b981, #059669); padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.5);">
            <p style="margin: 0 0 10px 0; font-size: 14px; opacity: 0.9;">Total Transaksi</p>
            <h3 style="margin: 0; font-size: 24px;">{{ $jumlahTransaksi }} Nota</h3>
        </div>

        <!-- Kartu 3: Total Produk -->
        <div
            style="background: white; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <p style="margin: 0 0 10px 0; font-size: 14px; color: #64748b;">Produk Tersedia</p>
            <h3 style="margin: 0; font-size: 24px; color: #1e293b;">{{ $totalProduk }} Item</h3>
        </div>

        <!-- Kartu 4: Total Kategori -->
        <div
            style="background: white; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <p style="margin: 0 0 10px 0; font-size: 14px; color: #64748b;">Kategori Produk</p>
            <h3 style="margin: 0; font-size: 24px; color: #1e293b;">{{ $totalKategori }} Jenis</h3>
        </div>

    </div>

    <!-- Menampilkan 5 Stok Terendah untuk peringatan -->
    <div style="background: #fff0f2; border: 1px solid #ffe4e6; padding: 20px; border-radius: 12px;">
        <h3 style="margin-top: 0; color: #be123c;">⚠️ Peringatan Stok Menipis</h3>

        <table
            style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; margin-top: 15px;">
            <thead>
                <tr style="text-align: left; background-color: #ffe4e6;">
                    <th style="padding: 12px;">Nama Produk</th>
                    <th style="padding: 12px;">Kategori</th>
                    <th style="padding: 12px;">Harga</th>
                    <th style="padding: 12px; width: 100px;">Sisa Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stokTipis as $stok)
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #ffe4e6;">{{ $stok->name }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #ffe4e6;">{{ $stok->category->name ?? '-' }}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #ffe4e6;">Rp
                            {{ number_format($stok->price, 0, ',', '.') }}
                        </td>
                        <!-- Logika blade warna teks otomatis (Merah mati jika 0, Kuning jika mau habis) -->
                        <td
                            style="padding: 12px; border-bottom: 1px solid #ffe4e6; font-weight: bold; color: {{ $stok->stock == 0 ? '#ef4444' : '#eab308' }};">
                            {{ $stok->stock }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- KANVAS GRAFIK (Lebar area dimaksimalkan untuk bulan) -->
    <div
        style="background: white; padding: 25px; border-radius: 12px; margin-top: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        <h3 style="margin-top: 0; color: #1e293b;">Tren Pendapatan 30 Hari Terakhir</h3>
        <!-- Perhatikan id "revenueChart", ini kunci penarikannya -->
        <canvas id="revenueChart" height="80"></canvas>
    </div>

    <!-- 1. Panggil Pustaka Global Chart.js dari Internet -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- 2. Eksekusi Pelukisnya -->
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');

        // Trik Menarik Data PHP ke Lingkungan JavaScript
        const labels = {!! json_encode($grafik_labels) !!};
        const dataUang = {!! json_encode($grafik_data) !!};

        // Buat Semprotan Gradien agar terkesan Modern (Ala Fintect UI)
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)'); // Biru pudar atas
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');   // Putih bersih bawah

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels, // Baris Tanggal
                datasets: [{
                    label: 'Omset Harian Rp ',
                    data: dataUang, // Baris Uang
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3b82f6',
                    pointRadius: 3, // Dibuat lebih kecil karena titiknya ada 30
                    fill: true,
                    tension: 0.4    // Lengkungan Lembut (Smooth Curve)
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false } // Sembunyikan legenda ganda
                },
                scales: {
                    y: {
                        beginAtZero: true, // Pastikan grafik mulai dari dasar 0
                        ticks: {
                            callback: function (value) {
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                }
            }
        });
    </script>

@endsection