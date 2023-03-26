@extends('layouts.master')

@section('css')
    <link href="{{ asset('css/item.css') }}" rel="stylesheet">
@endsection

@section('title')
    | {{ $product->name }}
@endsection

@section('container')

    @if (isset($admin) && $admin)
        <div class="modal fade" id="editProductModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit product</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="needs-validation" id="new-product-form" novalidate
                            action="/product/{{ $product->id }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="col-12">
                                <label for="inputName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="inputName" name="name"
                                    value="{{ $product->name }}" required>
                            </div>

                            <div class="col-12">
                                <label for="inputImg" class="form-label">URL Image</label>
                                <input type="file" class="form-control" id="inputImg" name="img"
                                    accept=".jpg,.jpeg,.png" required />
                            </div>

                            <div class="mb-3">
                                <label for="inputDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="inputDescription" name="description" rows="3" required>{{ $product->description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="categorySelectIdProduct" class="form-label">Category</label>
                                <select class="form-select selector-category" id="categorySelectIdProduct" name="category"
                                    data-categorySelected={{ $product->category_id }} required>
                                    <option selected disabled value="">Choose...</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="inputPrice" class="form-label">Price</label>
                                    <input type="number" step="0.01" min="0.01" class="form-control" id="inputPrice"
                                        name="price" value="{{ $product->price }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputStock" class="form-label">Stock</label>
                                    <input type="number" class="form-control" id="inputStock" value="{{ $product->stock }}"
                                        min="0" name="stock" required>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" form="new-product-form">Update</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div id="view">

        <div class="show-item">

            <div class="images-item">

                <div class="image-big">
                    <img src="{{ $product->img_url }}">
                </div>

            </div>

            <div class="info-item">

                <h1 class="name-item">{{ $product->name }}
                    @if (isset($admin) && $admin)
                        <button data-bs-toggle="modal" data-bs-target="#editProductModal" id="edit_product_button"><i
                                class="item_icon edit" id="edit_product"></i></button>
                    @endif
                </h1>

                <div class="precios">
                    <div class="precio-main">
                        <span class="base">{{ explode('.', strval($product->price))[0] }}</span>
                        <span class="cent">,@php
                            $value = explode('.', strval($product->price));
                            
                            if (count($value) > 1) {
                                $decimal = $value[1];
                            
                                $decimal = strlen(strval($decimal)) > 1 ? $decimal : $decimal . '0';
                            
                                echo $decimal;
                            } else {
                                echo '00';
                            }
                        @endphp</span>
                        <span class="euro">€</span>
                    </div>

                </div>

                <div class="ficha-item">

                    <div class="row">
                        <div class="column">
                            <span class="title-row">Envio</span>
                        </div>
                        <div class="column">
                            <span class="value-row envio free">Envío GRATIS</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column">
                            <span class="title-row">Devolución</span>
                        </div>
                        <div class="column">
                            <span class="value-row devolucion">Devolución GRATIS</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column">
                            <span class="title-row">Categoria</span>
                        </div>
                        <div class="column">
                            <span class="value-row category"><a
                                    href="/product/category/{{ $categoryProduct->id }}">{{ $categoryProduct->name }}</a></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column">
                            <span class="title-row">Cantidad</span>
                        </div>
                        <div class="column">
                            <div class="input-number">
                                <button class="bt-secondary less"><span>-</span></button>
                                <input type="text" name="nStock" id="nStock" data-max="{{ $product->stock }}"
                                    value=@if ($product->stock > 0) 1 @else 0 @endif>
                                <button class="bt-secondary more"><span>+</span></button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column">
                            <span class="title-row">Envío a domicilio</span>
                        </div>
                        <div class="column">
                            @if ($product->stock > 5)
                                <span class="value-row stock aviableStock">¡En stock!</span>
                            @elseif($product->stock > 0)
                                <span class="value-row stock littleStock">¡Últimas unidades!</span>
                            @else
                                <span class="value-row stock notStock">¡Sin stock!</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="column">
                            <span class="title-row">Descripción</span>
                        </div>
                        <div class="column">
                            <span class="value-row descripcion">{{ $product->description }}</span>
                        </div>
                    </div>

                </div>

                <div class="buttons">

                    <div class="buttons-anadir">

                        <form action="/shopping" method="POST">

                            @csrf

                            <input type="hidden" name="product_id" value="{{ $product->id }}"
                                class="product_shopping_hidden" />

                            @if ($product->stock > 0)
                                <input type="hidden" name="count" value="1" class="count_shopping_hidden" />
                            @endif

                            <button type="submit" class="carrito">
                                <span class="arrow-down"></span>
                                <span class="carrito-icono"></span>
                            </button>
                        </form>

                    </div>

                    <form action="/shopping/buy" method="POST">

                        @csrf

                        <input type="hidden" name="product_id" value="{{ $product->id }}"
                            class="product_shopping_hidden" />

                        @if ($product->stock > 0)
                            <input type="hidden" name="count" value="1" class="count_shopping_hidden" />
                        @endif

                        <button type="submit" class="comprar">
                            <span class="value">Buy</span>
                            <span class="arrow-right"></span>
                        </button>
                    </form>

                </div>

            </div>

        </div>

        @if (isset($otherProducts) && !empty($otherProducts))
            <h3 style="text-align: center; text-decoration: underline">Related products</h3>

            <div class="other-products show-product-container">

                @foreach ($otherProducts as $productOther)
                    <a href="/product/{{ $productOther['id'] }}" class="product-container">
                        <div class="card product-card">
                            <div class="card-top-bar">
                                <h3>{{ $productOther['name'] }}</h3>

                                @if (isset($admin) && $admin)
                                    <button class="btn-remove-product" data-productName="{{ $productOther['name'] }}"
                                        data-productId="{{ $productOther['id'] }}" data-bs-toggle="modal"
                                        data-bs-target="#removeProductModal"><i class="item_icon remove"></i></button>
                                @endif
                            </div>
                            <img src="{{ $productOther['img_url'] }}" class="card-img-top" alt="Product image">
                            <div class="card-body">
                                <h5 class="card-title product-price">{{ $productOther['price'] }}€</h5>
                                <h6>Stock: <span class="product-stock">{{ $productOther['stock'] }}</span></h6>
                                <p class="card-text">{{ $productOther['description'] }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach

            </div>
        @endif

        <div class="opinion-container">
            <h3 style="text-align: center; text-decoration: underline">Opinions</h3>

            <h6 style="text-align: center">Write your opinion</h6>
            <form class="opinion-form" method="POST" action="/product/{{$product->id}}/opinion">
                @csrf
                <div class="ec-stars-wrapper edit-calification">
                    <a class="star-calification" data-value="5" title="Vote with 5 stars">&#9733;</a>
                    <a class="star-calification" data-value="4" title="Vote with 4 stars">&#9733;</a>
                    <a class="star-calification" data-value="3" title="Vote with 3 stars">&#9733;</a>
                    <a class="star-calification" data-value="2" title="Vote with 2 stars">&#9733;</a>
                    <a class="star-calification" data-value="1" title="Vote with 1 stars">&#9733;</a>
                </div>
                <input type="hidden" id='calification' name='calification' value='0' />

                <textarea name="text" id="textOpinion" cols="30" rows="5"></textarea>

                <button type="submit" class="btn btn-success">Public</button>
            </form>

            <div class="opinion-list">

                @forelse ($opinions as $opinion)
                    @if ((isset($user) && $opinion->user->id == $user->id) || (isset($admin) && $admin))
                        <form method="POST" id="form-delete-opinion-{{$opinion->id}}" action="/product/opinion/{{ $opinion->id }}">
                            @csrf @method('DELETE')</form>
                    @endif
                    <div class="opinion">
                        <div class="opinion-top">
                            @if ((isset($user) && $opinion->user->id == $user->id) || (isset($admin) && $admin))
                                <button type="submit" class="delete" form="form-delete-opinion-{{$opinion->id}}"><i
                                        class="item_icon cross"></i></button>
                            @endif
                            <span class="opinion-user">{{ $opinion->user->name }}</span>
                            <div class="ec-stars-wrapper">
                                @for ($i = 5; $i > 0; $i--)
                                    <a class="star-calification @if ($i == $opinion->calification) selected @endif"
                                        data-value="{{ $i }}"
                                        title="Vote with {{ $i }} stars">&#9733;</a>
                                @endfor
                            </div>
                        </div>

                        <p class="opinion-text">{{ $opinion->text }}</p>

                    </div>
                @empty
                    <h5 style="text-align: center; margin-top: 25px;">No opinions yet</h5>
                @endforelse

            </div>
        </div>

    </div>

@endsection

@section('script')
    <script>
        const calificationContainer = document.querySelector(".opinion-container .ec-stars-wrapper");

        const calificationInput = document.getElementById('calification');

        calificationContainer.querySelectorAll("a.star-calification").forEach(n => n.addEventListener("click", e => {

            const selected = calificationContainer.querySelector("a.star-calification.selected");
            if (!!selected) selected.classList.remove("selected");
            e.target.classList.add("selected");

            calificationInput.value = e.target.dataset.value;
        }));
    </script>

    <script>
        const plusStock = document.querySelector(".input-number .bt-secondary.more");
        const minusStock = document.querySelector(".input-number .bt-secondary.less");

        const stockInput = document.querySelector(".input-number #nStock");

        const count_shopping = document.querySelectorAll('.count_shopping_hidden');

        const updateCount = () => count_shopping.forEach(n => n.value = stockInput.value);

        minusStock.addEventListener('click', () => {

            if (parseInt(stockInput.value) > 1) stockInput.value--;
            updateCount()
        });

        plusStock.addEventListener('click', () => {

            if (parseInt(stockInput.value) < parseInt(stockInput.dataset.max)) stockInput.value++;
            updateCount()
        });

        stockInput.addEventListener("blur", () => {

            if (parseInt(stockInput.value) < 1) stockInput.value = 1;
            else if (parseInt(stockInput.value) > parseInt(stockInput.dataset.max)) stockInput.value = parseInt(
                stockInput.dataset.max)
            updateCount()
        });


        document.querySelector(".carrito").disabled = stockInput.dataset.max == 0;
        document.querySelector(".comprar").disabled = stockInput.dataset.max == 0;

        let categorySelectId = document.querySelector('.selector-category');

        if (categorySelectId) {

            let categorySelected = categorySelectId.dataset.categoryselected;

            if (categorySelected) {

                Array.from(categorySelectId.children).forEach(n => {

                    n.removeAttribute('selected');

                    if (n.value == categorySelected) n.setAttribute('selected', true);

                });

            }
        }
    </script>
@endsection
