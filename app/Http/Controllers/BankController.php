<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankRequest;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    function create(BankRequest $req){

        $bank = new Bank();

        $bank->user_id = Auth::user()->id;
        $bank->account = $req->input('account');

        $bank->save();

        return back();

    }

    function update(BankRequest $req, $id){

        $bank = Bank::find($id);

        if($bank == null) abort(404, 'Bank account not found');

        $bank->account = $req->input('account');

        $bank->save();

        return back();

    }

    function remove($id){

        $bank = Bank::find($id);

        if($bank == null) abort(404, 'Bank account not found');

        $bank->delete();

        return back();

    }
}
