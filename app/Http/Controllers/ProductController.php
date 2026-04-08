<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        $products = Product::with('category')->get();
        
        return view('product.index', compact('products'));
    }

    public function create() {
        $categories = Category::all();

        return view('product.create', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'harga_modal' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'description' => 'nullable',
        ]);

        Product::create($request->all());

        return redirect('/produk')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id) {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'harga_modal' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',

        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect('/produk')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id) {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect('/produk')->with('success', 'Produk berhasil dihapus!');
    }
}
