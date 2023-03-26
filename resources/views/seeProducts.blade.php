@extends('layouts.master')

@section('css')
    <link href="{{asset('css/item.css')}}" rel="stylesheet">
@endsection

@section('title')
    
    | @if (isset($categoryProduct)) {{$categoryProduct->name}} @else All @endif

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

        <div class="modal fade" id="addProductModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add product</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" id="new-product-form" novalidate action="/product" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="col-12">
                            <label for="inputName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="inputName" name="name" required>
                        </div>
                        
                        <div class="col-12">
                            <label for="inputImg" class="form-label">Imatge</label>
                            <input type="file" class="form-control" id="inputImg" name="img" accept=".jpg,.jpeg,.png" required />
                        </div>

                        <div class="mb-3">
                            <label for="inputDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="inputDescription" name="description" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="categorySelectIdProduct" class="form-label">Category</label>
                            <select class="form-select selector-category" id="categorySelectIdProduct" name="category" @if(isset($categoryProduct)) data-categorySelected={{$categoryProduct->id}} @endif required>
                                <option selected disabled value="">Choose...</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="inputPrice" class="form-label">Price</label>
                                <input type="number" step="0.01" min="0.01" class="form-control" id="inputPrice" name="price" required>
                            </div>
                            <div class="col-md-6">
                                <label for="inputStock" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="inputStock" value="0" min="0" name="stock" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success" form="new-product-form">Create</button>
                </div>
            </div>
            </div>
        </div>
    @endif

    <div class="category_container">

        @if (isset($categoryProduct))
            @if (isset($admin) && $admin)
                <form action="/category/{{$categoryProduct->id}}" class="edit_category_form" id="edit_category_form" style="display: none;" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="text" value="{{$categoryProduct->name}}" id="categoryName" name="name">
                    <button type="submit"><i class="item_icon save"></i></button>
                    <button type="button" class="btn_cancel"><i class="item_icon cancel"></i></button>
                </form>
            @endif
            <h1 class="title_category">{{$categoryProduct->name}}</h1>
            @if(isset($admin) && $admin) <i class="item_icon edit" id="edit_category"></i> @endif
        @else
            <h1 class="title_category">All products</h1> 
        @endif

    </div>

    @if (isset($admin) && $admin)

        <div class="button_new_container">
            <button class="btn-action-admin add" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="item_icon add"></i>Add new product</button>
        </div>

    @endif

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

@endsection

@section('script')

    <script>

        const form = document.getElementById('edit_category_form');

        if(form){

            const cancelBtn = form.querySelector('.btn_cancel');

            const titleCategory = document.querySelector('.title_category');
            const editCategory = document.querySelector('#edit_category');

            document.getElementById('edit_category').addEventListener("click", () => {
                form.style.display = ""
                titleCategory.style.display="none";
                editCategory.style.display="none";
            });

            cancelBtn.addEventListener("click", () => {
                form.style.display="none"
                titleCategory.style.display="";
                editCategory.style.display="";
            });
        }


        let categorySelectId = document.querySelector('.selector-category');

        let categorySelected = categorySelectId.dataset.categoryselected;

        if(categorySelected){

            Array.from(categorySelectId.children).forEach(n => {

                n.removeAttribute('selected');

                if(n.value == categorySelected) n.setAttribute('selected', true);

            });

        }

        const formProductModal = document.getElementById("remove-product-form")
        const productName = formProductModal.querySelector(".product-name");

        document.querySelectorAll(".btn-remove-product").forEach(n => n.addEventListener("click", e => {
            e.stopPropagation();
            e.preventDefault();

            let button = e.target.tagName === 'I' ? e.target.parentElement : e.target;

            productName.innerText = button.dataset.productname
            formProductModal.action = formProductModal.dataset.action + button.dataset.productid
        }));

    </script>

@endsection