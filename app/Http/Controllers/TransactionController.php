<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{

    public function addToCart (Request $request){

        $user_id = $request->user_id;
        $product_id = $request->product_id;
        $status = 'di keranjang';
        $price = $request->price;
        $quantity = $request->quantity;

        $stock = Product::find($product_id)->stock;

        if($stock <= 0){
            return redirect()-> back()->with('error', 'Stock habis');
        }

        $transaction = Transaction::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->where('status', 'di keranjang')
            ->first();

        if ($transaction) {
            // If the product is already in the cart, increase the quantity
            $transaction->quantity += $quantity;
            $transaction->save();
    
            return redirect()->back()->with('status', 'Success Add to Cart');

        } else {
            Transaction::create([
                'user_id' =>$user_id,
                'product_id' =>$product_id,
                'status' => $status,
                'price' =>$price,
                'quantity' =>$quantity,
            ]);
            return redirect()-> back()->with('status', 'Success Add to Cart');
        }
    }

    public function payNow() 
    {
        $status = 'dibayar';
        $order_id = 'INV_' . Auth::user()->id . date('YmdHis');
        
        $carts = Transaction::where('user_id', Auth::user()->id)->where('status', 'di keranjang')->get();
        $wallets = Wallet::where('status','selesai')->get();
            $credit = 0;
            $debit = 0;

            foreach($wallets as $wallet) {
                $credit += $wallet->credit;
                $debit += $wallet->debit;
            }
            $saldo = $credit - $debit;


        $total_debit = 0;

        foreach($carts as $cart) {
            $total_price = $cart->price * $cart->quantity;

            $total_debit += $total_price; 
        }
        if($saldo < $total_debit) 
        {
            return redirect()->back()->with('error','Saldo Anda Tidak Cukup');
        }
        else{
            Wallet::create([
                'user_id' => Auth::user()->id,
                'debit' => $total_debit,
                'description' => 'Pembelian Produk'
            ]);
        }

        foreach($carts as $cart) {
            Transaction::find($cart->id)->update([
                'status' => $status,
                'order_id' => $order_id
            ]);

            Product::find($cart->product->id)->update([
                'stock' => $cart->product->stock - $cart->quantity
            ]);
        }

        return redirect()->back()->with('status', 'Berhasil membayar transaksi');
    }

    public function download($order_id){
        $transactions = Transaction::where('order_id', $order_id)->get();
        $total_biaya = 0;

        foreach($transactions as $transaction){
            $total_price = $transaction->price * $transaction->quantity;
            $total_biaya += $total_price;
        }

        return view('receipt', compact('transactions','total_biaya'));
    }

    public function deleteBaskets($id){
        $delete = Transaction::find($id)->delete();

        if($delete){
            return redirect()->back()->with('status','Product berhasil dihapus');
        }
        return redirect()->back()->with('error','Product gagal dihapus');
    }
}
