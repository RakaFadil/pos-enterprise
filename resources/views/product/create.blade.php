@extends('layouts.app')

@section('content')
    <h2 style="margin-top: 0; margin-bottom: 24px; font-size: 18px;">Tambah Produk Baru</h2>

    <form action="/produk" method="POST">
        @csrf

        <div class="form-group">
            <label>Nama Produk</label>
            <input type="text" name="name" required placeholder="Contoh: Kopi Gula Aren">
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select name="category_id" required
                style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="price" required placeholder="Contoh: 15000">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Harga Modal (Rahasia Admin):</label>
                <input type="number" name="harga_modal" value="0" required min="0"
                    style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
            </div>

            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stock" required placeholder="Contoh: 50">
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" rows="4" placeholder="Deskripsi singkat..."></textarea>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 10px;">
            <button type="submit" class="btn">Simpan Produk</button>
            <a href="/produk" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection