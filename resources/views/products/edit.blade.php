@extends('layouts.app')

@section('content')

<div class="container">
    <h2 class="mb-4">Edit Produk</h2>

    {{-- Pesan validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <form
                action="{{ route('products.update', $product->id) }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PUT')

                {{-- Category --}}
                <div class="mb-3">
                    <label class="form-label">Category</label>

                    <select
                        name="category_id"
                        class="form-select"
                        required
                    >
                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama Produk --}}
                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $product->name) }}"
                        required
                    >
                </div>

                {{-- SKU --}}
                <div class="mb-3">
                    <label class="form-label">SKU</label>

                    <input
                        type="text"
                        name="sku"
                        class="form-control"
                        value="{{ old('sku', $product->sku) }}"
                        required
                    >
                </div>

                {{-- Harga --}}
                <div class="mb-3">
                    <label class="form-label">Harga</label>

                    <input
                        type="number"
                        name="price"
                        class="form-control"
                        value="{{ old('price', $product->price) }}"
                        min="0"
                        required
                    >
                </div>

                {{-- Stock --}}
                <div class="mb-3">
                    <label class="form-label">Stock</label>

                    <input
                        type="number"
                        name="stock"
                        class="form-control"
                        value="{{ old('stock', $product->stock) }}"
                        min="0"
                        required
                    >
                </div>

                {{-- Gambar Lama --}}
                <div class="mb-3">
                    <label class="form-label">Gambar Saat Ini</label>

                    <div>
                        @if ($product->image)
                            <img
                                src="{{ asset('storage/' . $product->image) }}"
                                alt="Gambar {{ $product->name }}"
                                id="currentImage"
                                class="img-thumbnail mb-2"
                                style="width: 150px; height: 150px; object-fit: cover;"
                            >
                        @else
                            <p class="text-muted" id="noImage">
                                Produk belum memiliki gambar.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Upload Gambar Baru --}}
                <div class="mb-3">
                    <label for="image" class="form-label">
                        Ganti Gambar Produk
                    </label>

                    <input
                        type="file"
                        name="image"
                        id="image"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <small class="text-muted">
                        Kosongkan jika tidak ingin mengganti gambar.
                        Format JPG, JPEG, PNG, atau WEBP, maksimal 2 MB.
                    </small>
                </div>

                {{-- Preview Gambar Baru --}}
                <div class="mb-3" id="previewContainer" style="display: none;">
                    <label class="form-label">Preview Gambar Baru</label>
                    <div>
                        <img
                            src=""
                            id="imagePreview"
                            alt="Preview gambar baru"
                            class="img-thumbnail"
                            style="width: 150px; height: 150px; object-fit: cover;"
                        >
                    </div>
                </div>

                {{-- Tombol --}}
                <button type="submit" class="btn btn-primary">
                    Update
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
</div>

{{-- Preview gambar sebelum diunggah --}}
<script>
    document.getElementById('image').addEventListener('change', function (event) {
        const file = event.target.files[0];
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('previewContainer');

        if (file) {
            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (!allowedTypes.includes(file.type)) {
                alert('Format gambar harus JPG, JPEG, PNG, atau WEBP.');
                event.target.value = '';
                container.style.display = 'none';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran gambar maksimal 2 MB.');
                event.target.value = '';
                container.style.display = 'none';
                return;
            }

            preview.src = URL.createObjectURL(file);
            container.style.display = 'block';
        } else {
            preview.src = '';
            container.style.display = 'none';
        }
    });
</script>

@endsection
