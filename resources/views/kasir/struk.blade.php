<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Struk {{ $transaction->transaction_number }}
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            background: #f1f3f5;
            font-family: Arial, Helvetica, sans-serif;
            color: #212529;
        }

        .receipt-wrapper {
            width: 80mm;
            max-width: 100%;
            margin: auto;
        }

        .receipt {
            background: white;
            padding: 15px;
        }

        .store {
            text-align: center;
        }

        .store-name {
            font-size: 18px;
            font-weight: bold;
        }

        .store-info {
            margin-top: 5px;
            font-size: 11px;
            line-height: 1.5;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .transaction-info {
            font-size: 11px;
            line-height: 1.6;
        }

        .item {
            margin-bottom: 8px;
            font-size: 11px;
        }

        .item-name {
            font-weight: bold;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            gap: 5px;
        }

        .summary {
            font-size: 11px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }

        .grand-total {
            font-size: 14px;
            font-weight: bold;
        }

        .payment {
            margin-top: 10px;
            font-size: 11px;
        }

        .thank-you {
            text-align: center;
            margin-top: 15px;
            font-size: 11px;
        }

        .actions {
            width: 80mm;
            max-width: 100%;
            margin: 15px auto;
            display: flex;
            gap: 8px;
        }

        .actions button {
            flex: 1;
            border: none;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-print {
            background: #198754;
            color: white;
        }

        .btn-new {
            background: #0d6efd;
            color: white;
        }

        @media print {

            @page {
                size: 80mm auto;
                margin: 0;
            }

            body {
                background: white;
                padding: 0;
            }

            .receipt-wrapper {
                width: 80mm;
            }

            .receipt {
                padding: 8px;
            }

            .actions {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="receipt-wrapper">

    <div class="receipt">

        <div class="store">

            <div class="store-name">
                TOKO RETAIL MAKMUR
            </div>

            <div class="store-info">
                Jl. Contoh No. 123<br>
                Jember<br>
                Telp. 0812-xxxx-xxxx
            </div>

        </div>

        <div class="line"></div>

        <div class="transaction-info">

            <div>
                No. Transaksi :
                {{ $transaction->transaction_number }}
            </div>

            <div>
                Tanggal :
                {{ $transaction->created_at->format('d-m-Y H:i') }}
            </div>

            <div>
                Pelanggan : Umum
            </div>

        </div>

        <div class="line"></div>

        @foreach($transaction->details as $detail)

            <div class="item">

                <div class="item-name">
                    {{ $detail->product_name }}
                </div>

                <div class="item-detail">

                    <span>
                        {{ $detail->qty }}
                        x
                        Rp {{ number_format($detail->price, 0, ',', '.') }}
                    </span>

                    <span>
                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                    </span>

                </div>

            </div>

        @endforeach

        <div class="line"></div>

        <div class="summary">

            <div class="summary-row">
                <span>Subtotal</span>
                <span>
                    Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}
                </span>
            </div>

            <div class="summary-row">
                <span>Diskon</span>
                <span>
                    Rp {{ number_format($transaction->discount, 0, ',', '.') }}
                </span>
            </div>

            <div class="summary-row">
                <span>Pajak</span>
                <span>
                    Rp {{ number_format($transaction->tax, 0, ',', '.') }}
                </span>
            </div>

            <div class="summary-row">
                <span>Biaya Lain</span>
                <span>
                    Rp {{ number_format($transaction->other_fee, 0, ',', '.') }}
                </span>
            </div>

            <div class="line"></div>

            <div class="summary-row grand-total">
                <span>TOTAL</span>
                <span>
                    Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                </span>
            </div>

        </div>

        <div class="line"></div>

        <div class="payment">

            <div class="summary-row">
                <span>Pembayaran</span>
                <span>
                    {{ $transaction->payment_method }}
                </span>
            </div>

            <div class="summary-row">
                <span>Dibayar</span>
                <span>
                    Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}
                </span>
            </div>

            <div class="summary-row">
                <span>Kembalian</span>
                <span>
                    Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}
                </span>
            </div>

        </div>

        <div class="line"></div>

        <div class="thank-you">

            <strong>TERIMA KASIH</strong>

            <br>

            Barang yang sudah dibeli
            tidak dapat dikembalikan.

        </div>

    </div>

    <div class="actions">

        <button
            class="btn-print"
            onclick="window.print()">
            🖨 Cetak Struk
        </button>

        <button
            class="btn-new"
            onclick="window.location.href='{{ route('kasir.index') }}'">
            Transaksi Baru
        </button>

    </div>

</div>

<script>
    window.addEventListener('load', function () {
        window.print();
    });
</script>

</body>

</html>
