<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link href="{{asset('css/styles.css')}}" rel="stylesheet">
    <link href="{{asset('css/header.css')}}" rel="stylesheet">
    @yield('css')
    <title>eShop @yield('title')</title>
</head>
<body>
    
    @if (isset($admin) && $admin)

        <div class="modal fade" id="addCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add category</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" id="new-category-form" novalidate action="/category" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Category name</label>
                            <input type="text" class="form-control" id="categoryName" name="name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success" form="new-category-form">Create</button>
                </div>
            </div>
            </div>
        </div>

        <div class="modal fade" id="removeCategoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" id="remove-category-form" novalidate data-action="/category/" action="/category/" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="mb-3">
                            <label for="categorySelectId" class="form-label">Category</label>
                            <select class="form-select" id="categorySelectId" required>
                                <option selected disabled value="">Choose...</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                              </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger" form="remove-category-form">Remove</button>
                </div>
            </div>
            </div>
        </div>

    @endif

    <header class="main-header">

        <div class="header_container">
            <div class="menu_container">
                <i class="item_icon menu"></i>
                <div class="desplegable_menu_container">

                    <div class="menu_title_container">
                        <h3>Categories</h3>
                    </div>

                    @if (isset($admin) && $admin)
                        <div class="cr-admin-container">                  
                            <button class="btn-action-admin add" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                <i class="item_icon add"></i>Add category
                            </button>
                            <button class="btn-action-admin remove-category" data-bs-toggle="modal" data-bs-target="#removeCategoryModal">
                                <i class="item_icon remove-category"></i>Remove category
                            </button>
                        </div>
                    @endif

                    <ul class="list-categories">
                        <li><a href="/product">All products</a></li>
                        @foreach ($categories as $category)
                            <li><a href="/product/category/{{$category->id}}">{{$category->name}}</a></li>
                        @endforeach
                    </ul>

                </div>
            </div>
            
            <div class="title_container">
                <h1 class="title"><a href="/">DIAGNE LUXE</a></h1>
            </div>

            <div class="user_container">

                <ul class="list-items">
                    @if (isset($admin) && $admin)
                    <li><a class="item_icon admin_users" href="/user/control"></a></li>
                    @endif
                    <li><a class="item_icon user item_text" href="/dashboard">
                        <span class="text">
                            @if (isset($user))
                                {{$user->name}}
                            @endif
                        </span>
                    </a></li>
                    <li><a class="item_icon shop item_text" href="/shopping ">
                        @if (isset($shoppingCount) && $shoppingCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{$shoppingCount > 99 ? '99+' : $shoppingCount }}
                                <span class="visually-hidden">Products ready to buy</span>
                            </span>   
                        @endif

                    </a></li>
                </ul>

            </div>
        </div>


    </header>

    @if (session('notification'))
        
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img src="{{session('notificationImage')}}" class="rounded me-2" alt="...">
                <strong class="me-auto">Purchase</strong>
                <small>just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{session('notification')}}
            </div>
            </div>
        </div>

    @endif

    <div class="main-container">
        @yield('container')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

    @if (session('notification'))
        <script>
            new bootstrap.Toast(document.getElementById('liveToast')).show()
        </script>
    @endif


    <script src="{{asset('js/form_validation.js')}}"></script>

    @if (isset($admin) && $admin)

        <script>
            
            document.getElementById('remove-category-form').addEventListener('submit', e => {

                e.stopPropagation();
                e.preventDefault();

                let form = e.target;

                let id = form.querySelector('#categorySelectId').value;

                if(id.toString().length){
                    form.action = form.dataset.action + id

                    form.submit();
                }

            });

        </script>

    @endif

    @yield('script')
</body>
</html>