@extends('layouts.master')

@section('css')
    <link href="{{asset('css/item.css')}}" rel="stylesheet">
@endsection

@section('container')

@if (isset($admin) && $admin)
    <div class="modal fade" id="removeProductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="remove-product-form" novalidate data-action="/product/" action="/product/" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="mb-3">
                        <h5>¿Are you sure you want to delete the <i>'<span class="product-name"></span>'</i> product?</h5>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger" form="remove-product-form">Remove</button>
            </div>
        </div>
        </div>
    </div>

@endif

<div class="some-product-container">

    <h1>Some of our products</h1>

    <div class="show-product-container">
        @forelse ($products as $product)
            <a href="/product/{{$product->id}}" class="product-container">
                <div class="card product-card">
                    <div class="card-top-bar">
                        <h3>{{$product->name}}</h3>
                        
                        @if (isset($admin) && $admin)
            
                            <button class="btn-remove-product" data-productName="{{$product->name}}" data-productId="{{$product->id}}" data-bs-toggle="modal" data-bs-target="#removeProductModal"><i class="item_icon remove"></i></button>
                    
                        @endif
                    </div>
                    <img src="{{$product->img_url}}" class="card-img-top" alt="Product image">
                    <div class="card-body">
                    <h5 class="card-title product-price">@php
                        $total = strval($product->price);
                        
                        if(strpos($total, '.') !== false){
    
                            $totalEuro = explode(".", $total)[0];
                            $totalDecimal = explode(".", $total)[1];
    
                            if(strlen($totalDecimal) == 1) $total = "$totalEuro,$totalDecimal"."0";
                            else $total = str_replace(".", ",", $total);
    
                        }else $total = $total.",00";
    
                        echo $total;
                    @endphp€</h5>                
                    @if($product->stock > 5)
                        <span class="value-row stock aviableStock small-stock">¡En stock!</span>
                    @elseif($product->stock > 0)
                        <span class="value-row stock littleStock small-stock">¡Últimas unidades!</span>
                    @else
                        <span class="value-row stock notStock small-stock">¡Sin stock!</span>
                    @endif
                    <p class="card-text">{{$product->description}}</p>
                    </div>
                </div>
            </a>
        
        @empty
            <div class="empty-container">
                <h5 class="empty-list">No products yet</h5>
            </div>    
        @endforelse
    
    
    </div>
</div>

@endsection

@section('script')
    
    @if (isset($admin) && $admin)
        <script>

            const formProductModal = document.getElementById("remove-product-form")
            const productName = formProductModal.querySelector(".product-name");

            document.querySelectorAll(".btn-remove-product").forEach(n => n.addEventListener("click", e => {
                e.stopPropagation();
                e.preventDefault();

                alert("aa");

                let button = e.target.tagName === 'I' ? e.target.parentElement : e.target;

                productName.innerText = button.dataset.productname
                formProductModal.action = formProductModal.dataset.action + button.dataset.productid
            }));

        </script>

    @endif

@endsection