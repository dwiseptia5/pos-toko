<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'sku' => 'required|unique:products,sku',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $file = $request->file('image');

        if ($file) {
            $original = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_FILENAME
            );

            $ext = strtolower($file->getClientOriginalExtension());

            $filename = time() . '_' .
                preg_replace('/[^A-Za-z0-9_-]/', '_', $original);

            // Membuat ImageManager untuk Intervention Image v4
            $manager = new ImageManager(new Driver());

            // Membaca dan memotong gambar menjadi 800 x 800
            $image = $manager->decode($file->getRealPath())
                ->cover(800, 800);

            // Menentukan format hasil gambar
            if ($ext === 'png') {
                $encoded = $image->encodeUsingFormat(Format::PNG);
                $filename .= '.png';
            } else {
                $encoded = $image->encodeUsingFormat(
                    Format::JPEG,
                    quality: 85
                );

                $filename .= '.jpg';
            }

            $path = 'products/' . $filename;

            // Menyimpan gambar ke storage public
            Storage::disk('public')->put(
                $path,
                (string) $encoded
            );

            // Menyimpan lokasi gambar ke database
            $data['image'] = $path;
        } else {
            $data['image'] = null;
        }

        Product::create($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view(
            'products.edit',
            compact('product', 'categories')
        );
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'sku' => 'required|unique:products,sku,' . $product->id,
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $original = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_FILENAME
            );

            $ext = strtolower($file->getClientOriginalExtension());

            $filename = time() . '_' .
                preg_replace('/[^A-Za-z0-9_-]/', '_', $original);

            // Membuat ImageManager untuk Intervention Image v4
            $manager = new ImageManager(new Driver());

            // Membaca dan memotong gambar menjadi 800 x 800
            $image = $manager->decode($file->getRealPath())
                ->cover(800, 800);

            // Menentukan format hasil gambar
            if ($ext === 'png') {
                $encoded = $image->encodeUsingFormat(Format::PNG);
                $filename .= '.png';
            } else {
                $encoded = $image->encodeUsingFormat(
                    Format::JPEG,
                    quality: 85
                );

                $filename .= '.jpg';
            }

            $path = 'products/' . $filename;

            // Menyimpan gambar baru
            Storage::disk('public')->put(
                $path,
                (string) $encoded
            );

            // Menghapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete(
                    $product->image
                );
            }

            $data['image'] = $path;
        } else {
            // Mempertahankan gambar lama jika tidak upload gambar baru
            unset($data['image']);
        }

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diubah.');
    }

    public function destroy(Product $product)
    {
        // Menghapus file gambar jika tersedia
        if ($product->image) {
            Storage::disk('public')->delete(
                $product->image
            );
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
