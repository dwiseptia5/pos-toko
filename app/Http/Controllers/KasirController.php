<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                Transaction::whereDate(
                    'created_at',
                    today()
                )->count() + 1,
                3,
                '0',
                STR_PAD_LEFT
            );

        return view(
            'kasir.index',
            compact(
                'products',
                'transactionNumber'
            )
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

            'items' =>
                'required|array|min:1',

            'items.*.product_id' =>
                'required|exists:products,id',

            'items.*.qty' =>
                'required|integer|min:1',

            'payment_method' =>
                'required|string',

            'paid_amount' =>
                'required|numeric|min:0',

            'discount' =>
                'nullable|numeric|min:0',

            'tax' =>
                'nullable|numeric|min:0',

            'other_fee' =>
                'nullable|numeric|min:0',

        ]);


        DB::beginTransaction();


        try {

            /*
            |--------------------------------------------------------------------------
            | Hitung subtotal
            |--------------------------------------------------------------------------
            */

            $subtotal = 0;


            foreach ($request->items as $item) {

                $product =
                    Product::findOrFail(
                        $item['product_id']
                    );


                /*
                |--------------------------------------------------------------------------
                | Cek stok
                |--------------------------------------------------------------------------
                */

                if (
                    $product->stock <
                    $item['qty']
                ) {

                    throw new \Exception(
                        "Stok {$product->name} tidak mencukupi."
                    );

                }


                $subtotal +=
                    $product->price *
                    $item['qty'];
            }


            /*
            |--------------------------------------------------------------------------
            | Hitung pembayaran
            |--------------------------------------------------------------------------
            */

            $discount =
                $request->discount ?? 0;

            $tax =
                $request->tax ?? 0;

            $otherFee =
                $request->other_fee ?? 0;


            $grandTotal =
                max(
                    0,
                    $subtotal -
                    $discount +
                    $tax +
                    $otherFee
                );


            $paidAmount =
                $request->paid_amount;


            /*
            |--------------------------------------------------------------------------
            | Cek pembayaran
            |--------------------------------------------------------------------------
            */

            if (
                $paidAmount <
                $grandTotal
            ) {

                throw new \Exception(
                    'Uang pembayaran masih kurang.'
                );

            }


            $changeAmount =
                $paidAmount -
                $grandTotal;


            /*
            |--------------------------------------------------------------------------
            | Nomor transaksi
            |--------------------------------------------------------------------------
            */

            $transactionNumber =
                'TRX-' .
                date('Ymd') .
                '-' .
                str_pad(
                    Transaction::whereDate(
                        'created_at',
                        today()
                    )->count() + 1,
                    3,
                    '0',
                    STR_PAD_LEFT
                );


            /*
            |--------------------------------------------------------------------------
            | Simpan transaksi
            |--------------------------------------------------------------------------
            */

            $transaction =
                Transaction::create([

                    'transaction_number' =>
                        $transactionNumber,

                    'user_id' =>
                        null,

                    'customer_id' =>
                        null,

                    'subtotal' =>
                        $subtotal,

                    'discount' =>
                        $discount,

                    'tax' =>
                        $tax,

                    'other_fee' =>
                        $otherFee,

                    'grand_total' =>
                        $grandTotal,

                    'paid_amount' =>
                        $paidAmount,

                    'change_amount' =>
                        $changeAmount,

                    'payment_method' =>
                        $request->payment_method,

                    'status' =>
                        'completed',

                ]);


            /*
            |--------------------------------------------------------------------------
            | Simpan detail + kurangi stok
            |--------------------------------------------------------------------------
            */

            foreach (
                $request->items
                as $item
            ) {

                $product =
                    Product::findOrFail(
                        $item['product_id']
                    );


                $itemSubtotal =
                    $product->price *
                    $item['qty'];


                TransactionDetail::create([

                    'transaction_id' =>
                        $transaction->id,

                    'product_id' =>
                        $product->id,

                    'product_code' =>
                        $product->sku,

                    'product_name' =>
                        $product->name,

                    'price' =>
                        $product->price,

                    'qty' =>
                        $item['qty'],

                    'discount' =>
                        0,

                    'subtotal' =>
                        $itemSubtotal,

                ]);


                /*
                |--------------------------------------------------------------------------
                | Kurangi stok
                |--------------------------------------------------------------------------
                */

                $product->decrement(
                    'stock',
                    $item['qty']
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Simpan semua perubahan
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Arahkan ke halaman struk
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'kasir.struk',
                    $transaction->id
                )
                ->with(
                    'success',
                    'Pembayaran berhasil.'
                );


        } catch (\Exception $e) {

            DB::rollBack();


            return back()
                ->withInput()
                ->with(
                    'error',
                    'Transaksi gagal: ' .
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Halaman Struk
    |--------------------------------------------------------------------------
    */

    public function struk($id)
    {
        $transaction =
            Transaction::with(
                'details'
            )->findOrFail($id);


        return view(
            'kasir.struk',
            compact('transaction')
        );
    }
}
