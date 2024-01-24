<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function topupNow(Request $request){
        $user_id = Auth::user()->id;
        $credit = $request->credit;
        $status = 'proses';
        $description = 'Top Up';

        Wallet::create([
            'user_id'=> $user_id,
            'credit' => $credit,
            'status'=> $status,
            'description'=> $description,
        ]);

        return redirect()->back()->with('status','Proses topup');
    }

    public function withdrawNow(Request $request){
        $user_id = Auth::user()->id;
        $debit = $request->debit;
        $status = 'proses';
        $description = 'Withdraw';

        Wallet::create([
            'user_id'=> $user_id,
            'debit' => $debit,
            'status'=> $status,
            'description'=> $description,
        ]);

        return redirect()->back()->with('status','Proses Withdraw');
    }

    public function acceptRequest(Request $request){
        $wallet_id = $request->wallet_id;
 
        Wallet::find($wallet_id)->update([
         'status' => 'selesai'
        ]);
 
        return redirect()->back()->with('status','Berhasil acc');
     }
}
