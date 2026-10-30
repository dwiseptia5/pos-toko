<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>POS Toko</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Memuat Laravel Echo dan JavaScript --}}
    @vite(['resources/js/app.js'])
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">

        <a
            class="navbar-brand"
            href="{{ route('admin.index') }}"
        >
            POS Toko
        </a>

        <div>

            <a
                href="{{ route('admin.index') }}"
                class="btn btn-outline-light me-2"
            >
                Dashboard
            </a>

            <a
                href="{{ route('products.index') }}"
                class="btn btn-outline-light me-2"
            >
                Produk
            </a>

            <a
                href="{{ route('kasir.index') }}"
                class="btn btn-outline-light me-2"
            >
                Kasir
            </a>

            <form
                action="{{ route('logout') }}"
                method="POST"
                class="d-inline"
            >
                @csrf

                <button
                    type="submit"
                    class="btn btn-danger"
                >
                    Logout
                </button>
            </form>

        </div>
    </div>
</nav>

<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')

</div>

{{-- Listener Laravel Echo --}}
<script>
    
<script>
    console.log('HALAMAN POS BERHASIL DIMUAT');

    window.addEventListener('load', function () {

        console.log('HALAMAN SELESAI DIMUAT');

        if (window.Echo) {
            console.log('LARAVEL ECHO AKTIF');

            window.Echo.channel('orders')
                .listen('.order.placed', function (e) {
                    console.log('EVENT DITERIMA:', e);

                    alert('Transaksi baru berhasil diterima!');
                });

        } else {
            console.error('LARAVEL ECHO TIDAK AKTIF');
        }

    });
</script>

</body>
</html>
