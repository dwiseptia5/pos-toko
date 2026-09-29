@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Data Produk</h2>

    <a
        href="{{ route('products.create') }}"
        class="btn btn-primary"
    >
        + Tambah Produk
    </a>

</div>


<div class="card">

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead class="table-dark">

                <tr>

                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Category</th>
                    <th>SKU</th>
                    <th>Harga</th>
                    <th>Stock</th>
                    <th>Aksi</th>

                </tr>

            </thead>


            <tbody>

                @forelse($products as $product)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $product->name }}
                        </td>

                        <td>
                            {{ $product->category->name ?? '-' }}
                        </td>

                        <td>
                            {{ $product->sku }}
                        </td>

                        <td>
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>

                        <td>
                            {{ $product->stock }}
                        </td>

                        <td>

                            <a
                                href="{{ route('products.edit', $product->id) }}"
                                class="btn btn-warning btn-sm"
                            >
                                Edit
                            </a>


                            <form
                                action="{{ route('products.destroy', $product->id) }}"
                                method="POST"
                                class="d-inline"
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus produk ini?')"
                                >
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="text-center"
                        >
                            Belum ada data produk.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
