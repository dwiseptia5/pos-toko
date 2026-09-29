@extends('layouts.app')

@section('content')

<h2 class="mb-4">Tambah Produk</h2>


<div class="card">

    <div class="card-body">

        <form
            action="{{ route('products.store') }}"
            method="POST"
        >

            @csrf


            <div class="mb-3">

                <label class="form-label">
                    Category
                </label>

                <select
                    name="category_id"
                    class="form-select"
                    required
                >

                    <option value="">
                        -- Pilih Category --
                    </option>

                    @foreach($categories as $category)

                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="mb-3">

                <label class="form-label">
                    Nama Produk
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    placeholder="Masukkan nama produk"
                    required
                >

            </div>


            <div class="mb-3">

                <label class="form-label">
                    SKU
                </label>

                <input
                    type="text"
                    name="sku"
                    class="form-control"
                    placeholder="Contoh: SKU001"
                    required
                >

            </div>


            <div class="mb-3">

                <label class="form-label">
                    Harga
                </label>

                <input
                    type="number"
                    name="price"
                    class="form-control"
                    placeholder="Masukkan harga"
                    min="0"
                    required
                >

            </div>


            <div class="mb-3">

                <label class="form-label">
                    Stock
                </label>

                <input
                    type="number"
                    name="stock"
                    class="form-control"
                    placeholder="Masukkan stock"
                    min="0"
                    required
                >

            </div>


            <button
                type="submit"
                class="btn btn-primary"
            >
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

@endsection
