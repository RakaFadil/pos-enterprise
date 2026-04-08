@extends('layouts.app')

@section('content')
    <h2 style="margin-top: 0; margin-bottom: 24px; font-size: 18px;">Ubah Kategori: {{ $category->name }}</h2>

    <form action="/kategori/{{ $category->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="name" value="{{ $category->name }}" required>
        </div>

        <div class="form-group">
            <label>Deskripsi (Opsional)</label>
            <textarea name="description" rows="4">{{ $category->description }}</textarea>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 10px;">
            <button type="submit" class="btn">Simpan Perubahan</button>
            <a href="/kategori" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection