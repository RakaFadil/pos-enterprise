@extends('layouts.app')

@section('content')
    <h2 style="margin-top: 0; margin-bottom: 24px; font-size: 18px;">Tambah Kategori Baru</h2>

    <form action="/kategori" method="POST">
        @csrf

        <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="name" required placeholder="Contoh: Makanan">
        </div>

        <div class="form-group">
            <label>Deskripsi (Opsional)</label>
            <textarea name="description" rows="4" placeholder="Deskripsi singkat mengenai kategori ini..."></textarea>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 10px;">
            <button type="submit" class="btn">Simpan Data</button>
            <a href="/kategori" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection