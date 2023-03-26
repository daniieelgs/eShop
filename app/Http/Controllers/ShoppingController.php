<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyRequest;
use App\Http\Requests\ShoppingRequest;
use App\Models\Bank;
use App\Models\Category;
use App\Models\Shopping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingController extends Controller
{


    function seeAll(){

        $shoppings = $this->getShoppings();

        $user = Auth::user();
        $admin = $user->role === config('app.admin_role');

        return $this->view('shopping', ['shoppings' => $shoppings]);

    }

    function add(ShoppingRequest $req){

        $shop = Shopping::where('user_id', Auth::user()->id)->where('product_id', $req->input("product_id"))->get();

        if($shop == null || count($shop) == 0){

            $shop = $this->newShopping($req);

        }else {
            $shop = $shop[0];
            $shop->count += $req->input("count");
        }

        $shop->save();

        return redirect('/shopping');

    }

    function addAndBuy(ShoppingRequest $req){

        $this->add($req);

        return redirect('/buy');

    }

    function update(ShoppingRequest $req, $id){

        $shop = Shopping::find($id);

        if($shop == null) abort(404, 'Shopping not found');

        if($shop->product->stock == 0){
            $this->remove($shop->id);
            return back();
        }

        $count = $req->input("count") > $shop->product->stock ? $shop->product->stock : $req->input("count");

        $shop->product_id = $req->input("product_id");
        $shop->count = $count;

        $shop->save();

        return back();
    }

    function remove($id){
        
        $shop = Shopping::find($id);

        if($shop == null) abort(404, 'Shopping not found');

        $shop->delete();

        return back();
    }
 
    function dataBuy(){
        
        $shoppings = $this->getShoppings();

        $banks = Auth::user()->banks;

        $user = Auth::user();
        $admin = $user->role === config('app.admin_role');

        return $this->view('formDataBuy', ['shoppings' => $shoppings, 'banks' => $banks]);

    }

    function buyAll(BuyRequest $req){

        $bank = Bank::find($req->input('bank'));

        if($bank == null) abort(404, 'Account bank not found');
        if($bank->user_id != Auth::user()->id) abort(400, 'bad request');

        $account = $bank->account;
        $total = 0;

        $shoppings = $this->getShoppings();

        foreach($shoppings as $shopping){

            $shopping->product->stock -= $shopping->count;
            $shopping->product->save();
            $total += ($shopping->count * $shopping->product->price);
            $shopping->delete();

        }

        $total = strval($total);
        
        if(strpos($total, '.') !== false){

            $totalEuro = explode(".", $total)[0];
            $totalDecimal = explode(".", $total)[1];

            if(strlen($totalDecimal) == 1) $total = "$totalEuro,$totalDecimal"."0";
            else $total = str_replace(".", ",", $total);

        }else $total = $total.",00";

        $user = Auth::user();
        $admin = $user->role === config('app.admin_role');

        return redirect('/')->with(['notification' => "Your purchase of ".$total."€ has been made successfully in the account '$account'", 'notificationImage' => asset('images/icons/purchase.png')]);

    }

    private function getShoppings(){
        $shoppings = Auth::user()->shoppings;

        $shoppings_filtered = [];

        foreach($shoppings as $shopping){

            if($shopping->product->stock > 0){
                
                if($shopping->count > $shopping->product->stock) $shopping->count = $shopping->product->stock;

                array_push($shoppings_filtered, $shopping);

            }else $this->remove($shopping->id);

        }

        return $shoppings_filtered;
    }

    private function newShopping(ShoppingRequest $req){

        $shop = new Shopping();
        $shop->product_id = $req->input("product_id");
        $shop->user_id = Auth::user()->id;
        $shop->count = $req->input("count");

        return $shop;

    }

    private function view($view, $params = []){
        $user = Auth::user();
        $admin = $user->role === config('app.admin_role');
        $shoppingCount = count($user->shoppings->toArray());

        return view($view, array_merge(['user' => $user, 'admin' => $admin, 'categories' => Category::all(), 'shoppingCount' => $shoppingCount], $params));
    }
}
