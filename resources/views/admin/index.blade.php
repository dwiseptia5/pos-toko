@extends('layouts.app')

@section('content')

<h2 class="mb-4">Dashboard Admin</h2>


<div class="row">

    <div class="col-md-4 mb-3">

        <div class="card bg-primary text-white">

            <div class="card-body">

                <h5>Total Category</h5>

                <h2>{{ $categories->count() }}</h2>

            </div>

        </div>

    </div>


    <div class="col-md-4 mb-3">

        <div class="card bg-success text-white">

            <div class="card-body">

                <h5>Total Product</h5>

                <h2>{{ $products->count() }}</h2>

            </div>

        </div>

    </div>


    <div class="col-md-4 mb-3">

        <div class="card bg-warning">

            <div class="card-body">

                <h5>Total Supplier</h5>

                <h2>{{ $suppliers->count() }}</h2>

            </div>

        </div>

    </div>

</div>


<div class="card mt-4">

    <div class="card-body">

        <h4>Data Produk</h4>

        <p>
            Silakan klik tombol di bawah untuk mengelola produk.
        </p>

        <a
            href="{{ route('products.index') }}"
            class="btn btn-primary"
        >
            Kelola Produk
        </a>

    </div>

</div>

@endsection