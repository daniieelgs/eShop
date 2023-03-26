@extends('layouts.master')

@section('css')
    
    <link href="{{asset('css/shopping.css')}}" rel="stylesheet">    

@endsection

@section('title')
    
    | Shoppings

@endsection

@section('container')
    
    <h1 class="title-shopping">Shopping</h1>

    <div class="main">

        <div class="shopping-container">

            @forelse ($shoppings as $shopping)
                
                <div class="item-buy">
                    <img src="{{$shopping->product->img_url}}" class="image-product"/>
                    <div class="item-info">
                        <form action="/shopping/{{$shopping->id}}" method="POST" id="form-delete-shopping-{{$shopping->product->id}}">@csrf @method('DELETE')</form>
                        <button type="submit" form="form-delete-shopping-{{$shopping->product->id}}" id="btn-delete-shopping"><i class="item_icon cross"></i></button>
                        <h3 class="item-name"><a href="/product/{{$shopping->product->id}}">{{$shopping->product->name}}</a></h3>
                        <h4 class="item-price">@php
                           
                            $total = strval($shopping->product->price);
     
                             if(strpos($total, '.') !== false){
     
                                 $totalEuro = explode(".", $total)[0];
                                 $totalDecimal = explode(".", $total)[1];
     
                                 if(strlen($totalDecimal) == 1) $total = "$totalEuro,$totalDecimal"."0";
                                 else $total = str_replace(".", ",", $total);
     
                             }else $total = $total.",00";
     
                             echo $total;
                         @endphp€</h4>
                        <div class="item-count">
                            <form action="/shopping/{{$shopping->id}}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" value="{{$shopping->product->id}}" name="product_id" />
                                <label for="item-count-value">Quantity:</label>
                                <input type="number" min="0" value="{{$shopping->count}}" id="item-count-value" name="count"/>
                                <button type="submit">Update</button>
                            </form>
                        </div>
                        <h4 class="item-total">Subtotal: @php
                           
                           $total = strval($shopping->product->price * $shopping->count);
    
                            if(strpos($total, '.') !== false){
    
                                $totalEuro = explode(".", $total)[0];
                                $totalDecimal = explode(".", $total)[1];
    
                                if(strlen($totalDecimal) == 1) $total = "$totalEuro,$totalDecimal"."0";
                                else $total = str_replace(".", ",", $total);
    
                            }else $total = $total.",00";
    
                            echo $total;
                        @endphp€</h4>
                    </div> 
                </div>
                
            @empty
                <div class="empty-container">
                    <h5 class="empty-list">Your cart is empty, try to add some <a href="/product">products</a></h5>
                </div>
            @endforelse

    

        </div>
    
        @if (count($shoppings) > 0)
            
            <div class="buy-container">
        
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
        
                <form action="/buy">
                    @csrf
                    <button type="submit">Buy</button>
                </form>
        
            </div>

        @endif

    </div>

@endsection