<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>POS Toko</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

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

</body>

</html>
