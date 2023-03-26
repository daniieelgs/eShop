@extends('layouts.master')

@section('css')
    <link href="{{asset('css/item.css')}}" rel="stylesheet">
    <link href="{{asset('css/shopping.css')}}" rel="stylesheet">    
@endsection

@section('title')
    
    | Buy

@endsection

@section('container')

<h1 class="title-shopping">Buy</h1>


<div class="data-buy-container">

    <form class="needs-validation data-buy" id="dataBuyForm" novalidate action="/buy" method="POST">
        @csrf
        <div class="row mb-3">
            <label for="inputUserName" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="inputUserName" value="{{$user->name}}" name="name" required>
            </div>
        </div>
    
        <div class="row mb-3">
          <label for="inputUserEmail" class="col-sm-2 col-form-label">Email</label>
          <div class="col-sm-10">
            <input type="email" class="form-control" id="inputUserEmail" value="{{$user->email}}" name="email" required>
          </div>
        </div>
    
        <div class="row mb-3">
            <label for="bankSelect" class="col-sm-2 col-form-label">Bank Account</label>
            <div class="col-sm-10">
                <select class="form-select bank-select" id="bankSelect" name="bank" required>
                    <option selected disabled value="">Choose...</option>
                    @foreach ($banks as $bank)
                        <option value="{{$bank->id}}">{{$bank->account}}</option>
                    @endforeach
                    <option disabled>_________</option>
                    <option value="new">New</option>
                </select>
                {{-- <button class="new-bank btn-plain-text">New</button> --}}
                <button type="button" class="update-bank btn-plain-text" style="display: none">Update</button>
    
                <form></form>
    
                <div>
                    <form id="formNewBank" class="new_bank-form" style="display: none" action="/bank" method="POST">
                        @csrf
                        <input type="text" name="account" required/>
                        <button type="submit" form="formNewBank" class="new-bank btn-plain-text">Save</button>
                        <button type="button" class="btn-plain-text btn-cancel-bank">Cancel</button>
                    </form>
                </div>
                
                <div>
                    <form id="formUpdateBank" class="update_bank-form" style="display: none" action="/bank/" data-action="/bank/" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="text" name="account" required/>
                        <button type="submit" form="formUpdateBank" class="save-bank btn-plain-text">Save</button>
                        <button type="button" class="btn-plain-text btn-cancel-bank">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    
        <h2>@php
                    
            $products = 0;
            $price = 0;

            foreach ($shoppings as $shopping) {
                $products += $shopping->count;
                $price += ($shopping->product->price * $shopping->count);
            }

            $total = strval($price);

            if(strpos($total, '.') !== false){

                $totalEuro = explode(".", $total)[0];
                $totalDecimal = explode(".", $total)[1];

                if(strlen($totalDecimal) == 1) $total = "$totalEuro,$totalDecimal"."0";
                else $total = str_replace(".", ",", $total);

            }else $total = $total.",00";

            echo "Total ($products prodcuts): $total"."€";

        @endphp</h2>

        <button type="submit" form="dataBuyForm" class="comprar data-buy-submit">
            <span class="value">Buy</span>
            <span class="arrow-right"></span>
        </button>
    </form>

</div>

@endsection

@section('script')
    
  <script>

    const bank_select = document.querySelector(".bank-select");

    const update_bank = document.querySelector(".update-bank");

    const new_bank_form = document.querySelector(".new_bank-form");
    const update_bank_form = document.querySelector(".update_bank-form");

    const inputUpdateBank = update_bank_form.querySelector('.update_bank-form input[type="text"]')

    new_bank_form.addEventListener("submit", e => {

        e.preventDefault();
        e.stopPropagation();

        new_bank_form.submit();

    });
    update_bank_form.addEventListener("submit", e => e.stopPropagation());

    bank_select.addEventListener("change", () => {

        if(bank_select.value == "new"){

            Array.from(bank_select.children)[0].selected = true;

            update_bank.style.display = "none"
            bank_select.style.display = "none";
            new_bank_form.style.display = "flex";

        }else update_bank.style.display = "";

    });

    update_bank.addEventListener("click", () => {

        const valueSelect = bank_select.value;

        update_bank.style.display = "none";

        bank_select.style.display = "none";
        update_bank_form.style.display = "flex";

        const selectedOption = bank_select.options[bank_select.selectedIndex];

        const selectedText = selectedOption.text;

        inputUpdateBank.value = selectedText;
        update_bank_form.action = update_bank_form.dataset.action + valueSelect;

    });    

    document.querySelectorAll(".btn-cancel-bank").forEach(n => n.addEventListener("click", e => {

        e.target.parentElement.style.display = "none";
        bank_select.style.display = "";

        if(bank_select.value != "new" && !!bank_select.value) update_bank.style.display = "";

    }));

  </script>

@endsection