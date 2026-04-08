@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; font-size: 18px;">Daftar Kategori Produk</h2>
        <a href="/kategori/tambah" class="btn">+ Tambah Baru</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td style="color: var(--text-muted);">#0{{ $category->id }}</td>
                    <td style="font-weight: 500;">{{ $category->name }}</td>
                    <td style="color: var(--text-muted);">{{ $category->description ?? '-' }}</td>

                    <td style="text-align: right;">
                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                            <a href="/kategori/{{ $category->id }}/edit" class="btn"
                                style="background-color: #6366f1; font-size: 11px; padding: 6px 12px;">Edit</a>
                            <form action="/kategori/{{ $category->id }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn"
                                    style="background-color: #ef4444; font-size: 11px; padding: 6px 12px; border: none; cursor: pointer;"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection