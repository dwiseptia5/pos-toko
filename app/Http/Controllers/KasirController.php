<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class KasirController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Halaman Kasir
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $products = Product::orderBy('name')->get();

        $transactionNumber = 'TRX-' .
            date('Ymd') .
            '-' .
            str_pad(
                Transaction::whereDate('created_at', today())->count() + 1,
                3,
                '0',
                STR_PAD_LEFT
            );

        return view(
            'kasir.index',
            compact('products', 'transactionNumber')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Simpan Transaksi
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'paid_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'other_fee' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Hitung subtotal dan periksa stok
            $subtotal = 0;

            foreach ($request->items as $item) {
                $product = Product::findOrFail(
                    $item['product_id']
                );

                if ($product->stock < $item['qty']) {
                    throw new \Exception(
                        "Stok {$product->name} tidak mencukupi."
                    );
                }

                $subtotal += $product->price * $item['qty'];
            }

            // Hitung total pembayaran
            $discount = $request->discount ?? 0;
            $tax = $request->tax ?? 0;
            $otherFee = $request->other_fee ?? 0;

            $grandTotal = max(
                0,
                $subtotal - $discount + $tax + $otherFee
            );

            $paidAmount = $request->paid_amount;

            if ($paidAmount < $grandTotal) {
                throw new \Exception(
                    'Uang pembayaran masih kurang.'
                );
            }

            $changeAmount = $paidAmount - $grandTotal;

            // Buat nomor transaksi
            $transactionNumber = 'TRX-' .
                date('Ymd') .
                '-' .
                str_pad(
                    Transaction::whereDate('created_at', today())->count() + 1,
                    3,
                    '0',
                    STR_PAD_LEFT
                );

            // Simpan transaksi
            $transaction = Transaction::create([
                'transaction_number' => $transactionNumber,
                'user_id' => null,
                'customer_id' => null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'other_fee' => $otherFee,
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'payment_method' => $request->payment_method,
                'status' => 'completed',
            ]);

            // Simpan detail transaksi dan kurangi stok
            foreach ($request->items as $item) {
                $product = Product::findOrFail(
                    $item['product_id']
                );

                $itemSubtotal = $product->price * $item['qty'];

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'product_code' => $product->sku,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'qty' => $item['qty'],
                    'discount' => 0,
                    'subtotal' => $itemSubtotal,
                ]);

                $product->decrement('stock', $item['qty']);
            }

            // Simpan seluruh perubahan database
            DB::commit();

        } catch (\Throwable $e) {

            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Transaksi gagal: ' . $e->getMessage()
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Acara 31-32: Kirim Event Broadcasting
        |--------------------------------------------------------------------------
        */

        try {
            event(new OrderPlaced([
                'order_id' => $transaction->id,
                'transaction_number' => $transaction->transaction_number,
                'total' => $transaction->grand_total,
                'payment_method' => $transaction->payment_method,
            ]));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('kasir.struk', $transaction->id)
            ->with('success', 'Pembayaran berhasil.');
    }

    /*
    |--------------------------------------------------------------------------
    | Halaman Struk
    |--------------------------------------------------------------------------
    */

    public function struk($id)
    {
        $transaction = Transaction::with('details')
            ->findOrFail($id);

        return view(
            'kasir.struk',
            compact('transaction')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Invoice PDF
    |--------------------------------------------------------------------------
    */

    public function cetakInvoice($id)
    {
        $transaction = Transaction::with('details')
            ->findOrFail($id);

        $pdf = Pdf::loadView(
            'invoices.invoice',
            compact('transaction')
        );

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download(
            'invoice-' . $transaction->transaction_number . '.pdf'
        );
    }
}
