@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Data Produk</h2>

    <a href="{{ route('products.create') }}" class="btn btn-primary">
        + Tambah Produk
    </a>
</div>

{{-- Pesan berhasil --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close"
                data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
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
                            <td>{{ $loop->iteration }}</td>

                            {{-- Gambar Produk --}}
                            <td class="text-center">
                                @if ($product->image)
                                    <img
                                        src="{{ asset('storage/' . $product->image) }}"
                                        alt="Gambar {{ $product->name }}"
                                        width="80"
                                        height="80"
                                        class="rounded border"
                                        style="object-fit: cover;"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                                    >

                                    <span class="text-danger small"
                                          style="display: none;">
                                        Gambar tidak ditemukan
                                    </span>
                                @else
                                    <span class="text-muted small">
                                        Belum ada gambar
                                    </span>
                                @endif
                            </td>

                            <td>{{ $product->name }}</td>

                            <td>{{ $product->category->name ?? '-' }}</td>

                            <td>{{ $product->sku }}</td>

                            <td>
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>

                            <td>{{ $product->stock }}</td>

                            <td>
                                <div class="d-flex gap-1">
                                    <a
                                        href="{{ route('products.edit', $product->id) }}"
                                        class="btn btn-warning btn-sm"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('products.destroy', $product->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus produk ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                Belum ada data produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection
