<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kasir Retail POS</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/chasier.css') }}">
</head>

<body>

<div class="pos-container">

    <!-- HEADER -->
    <header class="header">

        <div>
            <div class="store-name">
                TOKO RETAIL MAKMUR
            </div>

            <div class="store-info">
                Jl. Contoh No. 123 • Jember • Telp. 0812-xxxx-xxxx
            </div>
        </div>

        <div class="transaction-info">

            <div>No. Transaksi</div>

            <div class="transaction-number" id="transactionNumber">
                {{ $transactionNumber }}
            </div>

            <div id="currentDate"></div>

        </div>

    </header>


    <!-- ALERT -->

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


    <!-- MAIN -->

    <main class="main">

        <!-- LEFT -->

        <section>

            <!-- TAMBAH BARANG -->

            <div class="card">

                <div class="card-header">
                    Tambah Barang
                </div>

                <div class="card-body">

                    <div class="search-area">

                        <div class="search-input">

                            <input
                                type="text"
                                id="searchProduct"
                                placeholder="Cari nama barang / kode / barcode..."
                                autocomplete="off"
                            >

                            <div
                                class="product-results"
                                id="productResults">
                            </div>

                        </div>

                        <button
                            class="btn btn-primary"
                            type="button"
                            onclick="searchProduct()">
                            Cari
                        </button>

                    </div>


                    <!-- PELANGGAN -->

                    <div class="customer-area">

                        <div class="form-group">

                            <label>Pelanggan</label>

                            <select
                                class="form-control"
                                id="customerType">

                                <option value="Umum">
                                    Umum
                                </option>

                                <option value="Pelanggan Member">
                                    Pelanggan Member
                                </option>

                                <option value="Member VIP">
                                    Member VIP
                                </option>

                            </select>

                        </div>


                        <div class="form-group">

                            <label>No. Member / HP</label>

                            <input
                                type="text"
                                class="form-control"
                                id="customerNumber"
                                placeholder="Opsional"
                            >

                        </div>

                    </div>

                </div>

            </div>


            <!-- KERANJANG -->

            <div class="card cart-card">

                <div class="card-header">

                    <div class="cart-header">

                        <span>
                            Keranjang Belanja
                        </span>

                        <span id="itemCount">
                            0 Item
                        </span>

                    </div>

                </div>


                <div class="table-wrapper">

                    <table>

                        <thead>

                            <tr>

                                <th width="40">
                                    No
                                </th>

                                <th>
                                    Barang
                                </th>

                                <th width="120">
                                    Harga
                                </th>

                                <th width="150"
                                    class="text-center">
                                    Qty
                                </th>

                                <th width="140"
                                    class="text-right">
                                    Subtotal
                                </th>

                                <th width="50">
                                </th>

                            </tr>

                        </thead>


                        <tbody id="cartBody">

                            <tr>

                                <td
                                    colspan="6"
                                    class="empty-cart">

                                    Keranjang masih kosong.

                                    <br>

                                    Silakan cari atau scan barang.

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </section>


        <!-- RIGHT -->

        <aside>

            <!-- RINGKASAN -->

            <div class="card">

                <div class="card-header">
                    Ringkasan Pembayaran
                </div>

                <div class="card-body">

                    <div class="payment-summary">

                        <div class="summary-row">

                            <span>
                                Total Item
                            </span>

                            <strong id="totalQty">
                                0
                            </strong>

                        </div>


                        <div class="summary-row">

                            <span>
                                Subtotal
                            </span>

                            <strong id="subtotal">
                                Rp 0
                            </strong>

                        </div>


                        <div class="summary-row">

                            <span>
                                Diskon (%)
                            </span>

                            <input
                                type="number"
                                id="discountPercent"
                                value="0"
                                min="0"
                                max="100"
                                onchange="calculateTotal()"
                            >

                        </div>


                        <div class="summary-row">

                            <span>
                                Diskon (Rp)
                            </span>

                            <input
                                type="number"
                                id="discountAmount"
                                value="0"
                                min="0"
                                onchange="calculateTotal()"
                            >

                        </div>


                        <div class="summary-row">

                            <span>
                                Pajak / PPN
                            </span>

                            <input
                                type="number"
                                id="tax"
                                value="0"
                                min="0"
                                onchange="calculateTotal()"
                            >

                        </div>


                        <div class="summary-row">

                            <span>
                                Biaya Lain
                            </span>

                            <input
                                type="number"
                                id="otherFee"
                                value="0"
                                min="0"
                                onchange="calculateTotal()"
                            >

                        </div>


                        <!-- TOTAL -->

                        <div class="total-box">

                            <div class="total-label">
                                TOTAL AKHIR
                            </div>

                            <div
                                class="total-value"
                                id="grandTotal">
                                Rp 0
                            </div>

                        </div>


                        <!-- PEMBAYARAN -->

                        <div class="payment-box">

                            <div class="payment-label">
                                Uang Dibayar
                            </div>

                            <input
                                type="number"
                                id="payment"
                                class="payment-input"
                                placeholder="0"
                                oninput="calculateChange()"
                            >


                            <!-- NOMINAL CEPAT -->

                            <div class="quick-payment">

                                <button
                                    type="button"
                                    onclick="quickPayment(40000)">
                                    Rp 40.000
                                </button>

                                <button
                                    type="button"
                                    onclick="quickPayment(50000)">
                                    Rp 50.000
                                </button>

                                <button
                                    type="button"
                                    onclick="quickPayment(100000)">
                                    Rp 100.000
                                </button>

                                <button
                                    type="button"
                                    onclick="exactPayment()">
                                    Uang Pas
                                </button>

                            </div>


                            <!-- METODE PEMBAYARAN -->

                            <div class="payment-label payment-method-title">
                                Metode Pembayaran
                            </div>

                            <div class="payment-method">

                                <button
                                    type="button"
                                    class="active"
                                    onclick="selectPayment(this, 'Tunai')">
                                    Tunai
                                </button>

                                <button
                                    type="button"
                                    onclick="selectPayment(this, 'QRIS')">
                                    QRIS
                                </button>

                                <button
                                    type="button"
                                    onclick="selectPayment(this, 'Debit')">
                                    Debit
                                </button>

                                <button
                                    type="button"
                                    onclick="selectPayment(this, 'Kredit')">
                                    Kredit
                                </button>

                                <button
                                    type="button"
                                    onclick="selectPayment(this, 'E-Wallet')">
                                    E-Wallet
                                </button>

                                <button
                                    type="button"
                                    onclick="selectPayment(this, 'Transfer')">
                                    Transfer
                                </button>

                            </div>


                            <!-- KEMBALIAN -->

                            <div
                                class="change-box"
                                id="changeBox">

                                <div class="change-label">
                                    KEMBALIAN
                                </div>

                                <div
                                    class="change-value"
                                    id="change">
                                    Rp 0
                                </div>

                            </div>


                            <!-- ACTION -->

                            <div class="action-area">

                                <button
                                    type="button"
                                    class="btn btn-warning"
                                    onclick="holdTransaction()">
                                    Tahan
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-danger"
                                    onclick="cancelTransaction()">
                                    Batal
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-success btn-pay"
                                    onclick="processPayment()">
                                    BAYAR & CETAK
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </aside>

    </main>

</div>


<script>

    const products = @json($products);

</script>

<script src="{{ asset('js/chasier.js') }}"></script>

</body>

</html>