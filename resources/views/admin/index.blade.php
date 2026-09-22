<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin POS Toko</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand">
            POS Toko
        </span>

        <span class="text-white">
            Admin
        </span>
    </div>
</nav>

<div class="container mt-4">

    <h2 class="mb-4">Dashboard Admin</h2>


    {{-- ================= TOTAL DATA ================= --}}
    <div class="row mb-4">

        {{-- Total Category --}}
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5>Total Category</h5>
                    <h2>{{ $categories->count() }}</h2>
                    <p class="mb-0">Jumlah kategori</p>
                </div>
            </div>
        </div>

        {{-- Total Product --}}
        <div class="col-md-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5>Total Product</h5>
                    <h2>{{ $products->count() }}</h2>
                    <p class="mb-0">Jumlah produk</p>
                </div>
            </div>
        </div>

        {{-- Total Supplier --}}
        <div class="col-md-4">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body">
                    <h5>Total Supplier</h5>
                    <h2>{{ $suppliers->count() }}</h2>
                    <p class="mb-0">Jumlah supplier</p>
                </div>
            </div>
        </div>

    </div>


    {{-- ================= CATEGORY ================= --}}
    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Category</h5>

            <button class="btn btn-primary btn-sm">
                + Tambah Category
            </button>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">

                <thead class="table-dark">
                    <tr>
                        <th width="10%">No</th>
                        <th>Nama Category</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($categories as $category)

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="2" class="text-center">
                                Belum ada data category.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>


    {{-- ================= PRODUCT ================= --}}
    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Product</h5>

            <button class="btn btn-primary btn-sm">
                + Tambah Product
            </button>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">

                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Category</th>
                        <th>Nama Product</th>
                        <th>SKU</th>
                        <th>Harga</th>
                        <th>Stok</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($products as $product)

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $product->category->name ?? '-' }}
                            </td>

                            <td>{{ $product->name }}</td>

                            <td>{{ $product->sku }}</td>

                            <td>
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>

                            <td>{{ $product->stock }}</td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center">
                                Belum ada data product.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>


    {{-- ================= SUPPLIER ================= --}}
    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Supplier</h5>

            <button class="btn btn-primary btn-sm">
                + Tambah Supplier
            </button>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">

                <thead class="table-dark">
                    <tr>
                        <th width="10%">No</th>
                        <th>Nama Supplier</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($suppliers as $supplier)

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>{{ $supplier->address }}</td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center">
                                Belum ada data supplier.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

</body>
</html>