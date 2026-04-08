@extends('layouts.app')

@section('content')
    <!-- Pustaka TomSelect (Sihir Pencarian) -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <div style="display: flex; gap: 24px;">

        <!-- AREA KIRI: Form Penyesuaian Stok (35%) -->
        <div
            style="flex: 3.5; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-height: 500px;">
            <h2 style="margin-top: 0; font-size: 18px; color: #1e293b;">Penyesuaian Stok Baru</h2>

            <form action="/inventori" method="POST">
                @csrf
                <div style="margin-bottom: 15px;">

                    <label>Pilih Barang:</label>
                    <select name="product_id" id="product_id" required
                        style="width:100%; padding:8px; border: 1px solid #cbd5e1; border-radius:5px;">
                        <option value="">-- Cari Produk Gudang --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} (Sisa: {{ $p->stock }})</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 500; font-size: 14px;">Jenis Pergerakan:</label>
                    <select name="type" required
                        style="width:100%; padding:8px; border: 1px solid #cbd5e1; border-radius:5px;">
                        <option value="in">🟢 STOK MASUK (Restock Supplier)</option>
                        <option value="out">🔴 STOK KELUAR (Barang Rusak/ED)</option>
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 500; font-size: 14px;">Jumlah Barang:</label>
                    <input type="number" name="quantity" min="1" required
                        style="width:100%; padding:8px; border: 1px solid #cbd5e1; border-radius:5px;"
                        placeholder="Contoh: 50">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-weight: 500; font-size: 14px;">Keterangan / Alasan:</label>
                    <input type="text" name="description" required
                        style="width:100%; padding:8px; border: 1px solid #cbd5e1; border-radius:5px;"
                        placeholder="Contoh: Kiriman Truk Indofood">
                </div>

                <button type="submit" class="btn" style="width: 100%; justify-content: center; background-color: #3b82f6;">
                    💾 Save
                </button>
            </form>
        </div>

        <!-- AREA KANAN: Tabel Riwayat CCTV (65%) -->
        <div
            style="flex: 6.5; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h2 style="margin-top: 0; font-size: 18px; color: #1e293b;">Log Inventori</h2>

            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background-color: #f8fafc;">
                        <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Tanggal</th>
                        <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Nama Barang</th>
                        <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Status</th>
                        <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Alasan</th>
                        <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 13px; color: #64748b;">
                                {{ $log->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-weight: 500;">
                                {{ $log->product->name }}
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                                @if($log->type == 'in')
                                    <span
                                        style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">+{{ $log->quantity }}
                                        IN</span>
                                @else
                                    <span
                                        style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">-{{ $log->quantity }}
                                        OUT</span>
                                @endif
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 13px;">
                                {{ $log->description }}
                            </td>
                            <!-- Fitur Relasi User (Admin) bekerja dengan sukses di sini! -->
                            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 13px; color: #64748b;">
                                {{ $log->user->name ?? 'Anonim' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px; color: #94a3b8;">
                                Belum ada pergerakan barang secara manual.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <script>
        // Sihir untuk membuat Dropdown menjadi Searchable
        new TomSelect("#product_id", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    </script>
@endsection