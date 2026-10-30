<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>Invoice {{ $transaction->transaction_number }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h2 {
            margin-bottom: 5px;
        }

        .header p {
            margin-top: 0;
            color: #666;
        }

        .info {
            margin-bottom: 20px;
            line-height: 1.8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
        }

        th {
            background-color: #eeeeee;
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .summary {
            width: 55%;
            margin-left: auto;
            margin-top: 20px;
        }

        .summary td {
            padding: 8px;
        }

        .grand-total {
            font-weight: bold;
            background-color: #eeeeee;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 11px;
        }
    </style>
</head>

<body>

    <div class="header">

        <h2>TOKO RETAIL MAKMUR</h2>

        <p>Jl. Contoh No. 123, Jember</p>

        <h3>INVOICE PENJUALAN</h3>

    </div>


    <div class="info">

        <strong>No. Transaksi:</strong>
        {{ $transaction->transaction_number }}
        <br>

        <strong>Tanggal:</strong>
        {{ $transaction->created_at->format('d-m-Y H:i') }}
        <br>

        <strong>Pelanggan:</strong>
        Umum
        <br>

        <strong>Metode Pembayaran:</strong>
        {{ $transaction->payment_method }}
        <br>

        <strong>Status:</strong>
        {{ ucfirst($transaction->status) }}

    </div>


    <table>

        <thead>

            <tr>
                <th width="5%">No</th>
                <th>Nama Produk</th>
                <th width="12%">Jumlah</th>
                <th width="20%">Harga</th>
                <th width="22%">Subtotal</th>
            </tr>

        </thead>

        <tbody>

            @foreach ($transaction->details as $index => $detail)

                <tr>

                    <td class="center">
                        {{ $index + 1 }}
                    </td>

                    <td>
                        {{ $detail->product_name }}
                    </td>

                    <td class="center">
                        {{ $detail->qty }}
                    </td>

                    <td class="right">
                        Rp {{ number_format($detail->price, 0, ',', '.') }}
                    </td>

                    <td class="right">
                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>


    <table class="summary">

        <tr>

            <td>Subtotal</td>

            <td class="right">
                Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}
            </td>

        </tr>

        <tr>

            <td>Diskon</td>

            <td class="right">
                Rp {{ number_format($transaction->discount, 0, ',', '.') }}
            </td>

        </tr>

        <tr>

            <td>Pajak</td>

            <td class="right">
                Rp {{ number_format($transaction->tax, 0, ',', '.') }}
            </td>

        </tr>

        <tr>

            <td>Biaya Lain</td>

            <td class="right">
                Rp {{ number_format($transaction->other_fee, 0, ',', '.') }}
            </td>

        </tr>

        <tr class="grand-total">

            <td>GRAND TOTAL</td>

            <td class="right">
                Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
            </td>

        </tr>

        <tr>

            <td>Dibayar</td>

            <td class="right">
                Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}
            </td>

        </tr>

        <tr>

            <td>Kembalian</td>

            <td class="right">
                Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}
            </td>

        </tr>

    </table>


    <div class="footer">

        <strong>TERIMA KASIH TELAH BERBELANJA</strong>

        <p>
            Barang yang sudah dibeli tidak dapat dikembalikan.
        </p>

    </div>

</body>

</html>
