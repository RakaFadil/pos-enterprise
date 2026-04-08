@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="margin: 0; font-size: 18px;">Daftar Produk</h2>
        <a href="/produk/tambah" class="btn">+ Tambah Produk</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th style="text-align: right;">Harga Modal</th>
                <th style="text-align: right;">Harga Jual</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td style="font-weight: 500;">{{ $product->name }}</td>
                    <!-- Memanggil relasi kategori yang kita buat tadi -->
                    <td><span
                            style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">{{ $product->category->name }}</span>
                    </td>
                    <td>{{ $product->stock }} unit</td>
                    <!-- Menampilkan Harga Modal -->
                    <td style="text-align: right; color: #64748b;">Rp {{ number_format($product->harga_modal, 0, ',', '.') }}
                    </td>
                    <td style="text-align: right; font-weight: 600; color: #0f172a;">Rp
                        {{ number_format($product->price, 0, ',', '.') }}
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <!-- Tombol Edit -->
                            <a href="/produk/{{ $product->id }}/edit" class="btn"
                                style="background-color: #f59e0b; padding: 5px 10px; font-size: 11px; text-decoration: none;">Edit</a>

                            <!-- Form Hapus -->
                            <form action="/produk/{{ $product->id }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn"
                                    style="background-color: #ef4444; padding: 5px 10px; font-size: 11px; border: none; cursor: pointer;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection