<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Category::all() sama dengan query "SELECT * FROM categories"
        $categories = Category::all();

        // Kirim $categories ke file View yang bernama "category.index"
        return view('category.index', compact('categories'));
    }

    public function create()
    {
        return view('category.create');

    }

    public function store(Request $request)
    {
        // 1. Validasi: Pastikan nama kategori wajib diisi maksimal 255 huruf
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        // 2. Simpan ke database (Sangat singkat bukan?)
        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // 3. Alihkan (Redirect) kembali ke halaman daftar kategori
        return redirect('/kategori');
    }

    public function edit($id)
    {
        // Cari data kategori berdasarkan ID, jika tidak ada maka Error 404
        $category = Category::findOrFail($id);

        return view('category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        // 2. Cari data lama dan update nilainya
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // 3. Redirect
        return redirect('/kategori');
    }

    public function destroy($id)
    {
        // Hapus data berdasarkan ID
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect('/kategori');
    }
}
