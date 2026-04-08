@extends('layouts.app')

@section('content')
    <div
        style="max-width: 600px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        <h2 style="margin-top: 0; margin-bottom: 20px; font-size: 20px; color: #1e293b;">Edit Produk</h2>

        <form action="/produk/{{ $product->id }}" method="POST">
            @csrf
            @method('PUT') <!-- Sangat Penting: Untuk memberi tahu Laravel ini adalah UPDATE -->

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Kategori Barang</label>
                <select name="category_id" required
                    style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Nama Produk</label>
                <input type="text" name="name" value="{{ $product->name }}" required
                    style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>

            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Harga Modal</label>
                    <input type="number" name="harga_modal" value="{{ $product->harga_modal }}" required
                        style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Harga Jual</label>
                    <input type="number" name="price" value="{{ $product->price }}" required
                        style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Stok</label>
                <input type="number" name="stock" value="{{ $product->stock }}" required
                    style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn" style="flex: 1; justify-content: center;">💾 Simpan Perubahan</button>
                <a href="/produk" class="btn" style="background-color: #64748b; text-decoration: none;">Batal</a>
            </div>
        </form>
    </div>
@endsection