<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if(Auth::user()->role == 'siswa'){
            $products = Product::all();
            $wallets = Wallet::where('user_id', Auth::user()->id)->where('status', 'selesai')->get();
            $credit = 0;
            $debit = 0;

            foreach ($wallets as $wallet) {
                $credit += $wallet->credit;
                $debit += $wallet->debit;
            }
 
            $saldo = $credit - $debit;

            $carts = Transaction::where('status','di keranjang')->get();
            
            $total_biaya = 0;

            

            foreach($carts as $cart){
                $total_price = $cart->price * $cart->quantity;
                $total_biaya += $total_price;
            }

            $transactions = Transaction::where('status','dibayar')->where('user_id', Auth::user()->id)->orderBy('created_at','DESC')->paginate(5)->groupBy('order_id');
            $mutasi = Wallet::where('user_id', Auth::user()->id)->orderBy('created_at','DESC')->get();   
        
            return view('home', compact('saldo','products','carts','total_biaya','transactions','mutasi'));
        }

        if(Auth::user()->role == 'kantin'){
            $products = Product::all();
            $allProducts = Product::all()->count();
            $transactions = Wallet::where('description','Beli')->count();
            $transactionAll = Transaction::where('status', 'dibayar')->orderBy('created_at', 'DESC')->paginate(5)->groupBy('order_id');
            $wallets = Wallet::where('status','selesai')->get();
            $categories = Category::all();
            $credit = 0;
            $debit = 0;

            foreach($wallets as $wallet){
                $credit += $wallet->credit; 
                $debit += $wallet->debit; 
            }

            $saldo = $debit + $credit;

            return view('home', compact('products','allProducts','transactions','transactionAll','categories','saldo'));
        }

        if(Auth::user()->role == 'bank'){
            $wallets = Wallet::where('status', 'selesai')->get();
            $credit = 0;
            $debit = 0;
    
            foreach($wallets as $wallet){
                $credit += $wallet->credit;
                $debit += $wallet->debit;
            }

            $saldo = $credit - $debit;
            $nasabah = User::where('role', 'siswa')->get()->count();
            $transactions = Transaction::all()->groupBy('order_id')->count();
            $transactionAll = Transaction::where('status', 'dibayar')->orderBy('created_at', 'DESC')->paginate(5)->groupBy('order_id');
            $request_topup = Wallet::where('status','proses')->get();
            $mutasi = Wallet::where('status', 'selesai')->orderBy('created_at', 'DESC')->get();
            $creditAll = Wallet::where('debit')->get()->count();
            $debitAll = Wallet::where('credit')->get()->count();
            

            return view('home', compact('saldo','nasabah','transactions', 'transactionAll','request_topup','mutasi', 'creditAll', 'debitAll'));
        }
        
    }
}
