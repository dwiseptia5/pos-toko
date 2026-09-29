@extends('layouts.app')

@section('content')

<h2 class="mb-4">Edit Produk</h2>


<div class="card">

    <div class="card-body">

        <form
            action="{{ route('products.update', $product->id) }}"
            method="POST"
        >

            @csrf

            @method('PUT')


            <div class="mb-3">

                <label class="form-label">
                    Category
                </label>

                <select
                    name="category_id"
                    class="form-select"
                    required
                >

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            {{ $product->category_id == $category->id ? 'selected' : '' }}
                        >
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
                    value="{{ $product->name }}"
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
                    value="{{ $product->sku }}"
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
                    value="{{ $product->price }}"
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
                    value="{{ $product->stock }}"
                    min="0"
                    required
                >

            </div>


            <button
                type="submit"
                class="btn btn-primary"
            >
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

@endsection
