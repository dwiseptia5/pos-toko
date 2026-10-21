@extends('layouts.app')

@section('content')

<h2 class="mb-4">Tambah Produk</h2>

<div class="card">
    <div class="card-body">

        <form
            action="{{ route('products.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            {{-- Category --}}
            <div class="mb-3">
                <label class="form-label" for="category_id">
                    Category
                </label>

                <select
                    name="category_id"
                    id="category_id"
                    class="form-select"
                    required
                >
                    <option value="">-- Pilih Category --</option>

                    @foreach($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                @error('category_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nama Produk --}}
            <div class="mb-3">
                <label class="form-label" for="name">
                    Nama Produk
                </label>

                <input
                    type="text"
                    name="name"
                    id="name"
                    class="form-control"
                    placeholder="Masukkan nama produk"
                    value="{{ old('name') }}"
                    required
                >

                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- SKU --}}
            <div class="mb-3">
                <label class="form-label" for="sku">
                    SKU
                </label>

                <input
                    type="text"
                    name="sku"
                    id="sku"
                    class="form-control"
                    placeholder="Contoh: SKU001"
                    value="{{ old('sku') }}"
                    required
                >

                @error('sku')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Harga --}}
            <div class="mb-3">
                <label class="form-label" for="price">
                    Harga
                </label>

                <input
                    type="number"
                    name="price"
                    id="price"
                    class="form-control"
                    placeholder="Masukkan harga"
                    value="{{ old('price') }}"
                    min="0"
                    step="any"
                    required
                >

                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Stock --}}
            <div class="mb-3">
                <label class="form-label" for="stock">
                    Stock
                </label>

                <input
                    type="number"
                    name="stock"
                    id="stock"
                    class="form-control"
                    placeholder="Masukkan stock"
                    value="{{ old('stock') }}"
                    min="0"
                    required
                >

                @error('stock')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Gambar Produk --}}
            <div class="mb-3">
                <label for="image" class="form-label">
                    Gambar Produk
                </label>

                <input
                    type="file"
                    name="image"
                    id="image"
                    class="form-control"
                    accept=".jpg,.jpeg,.png,.webp"
                >

                <small class="text-muted">
                    Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                </small>

                @error('image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                <div class="mt-3">
                    <img
                        id="preview"
                        src="#"
                        alt="Preview Gambar Produk"
                        class="img-thumbnail d-none"
                        style="max-width: 200px; max-height: 200px;"
                    >
                </div>
            </div>

            {{-- Tombol --}}
            <button type="submit" class="btn btn-primary">
                Simpan
            </button>

            <a
                href="{{ route('products.index') }}"
                class="btn btn-secondary"
            >
                Kembali
            </a>

        </form>

    </div>
</div>

{{-- Preview gambar sebelum disimpan --}}
<script>
    document.getElementById('image').addEventListener('change', function () {
        const preview = document.getElementById('preview');
        const file = this.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
        } else {
            preview.src = '#';
            preview.classList.add('d-none');
        }
    });
</script>

@endsection
