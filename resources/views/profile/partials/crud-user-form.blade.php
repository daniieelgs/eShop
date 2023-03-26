
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
@endsection

<section>

    <div class="modal fade" id="removeUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/user/" method="POST" id="delete-user-form" data-action="/user/">
                    @csrf
                    @method('DELETE')
                    <div class="mb-3">
                        <h5>¿Are you sure you want to delete the <i>'<span class="user-name"></span>'</i> user?</h5>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger" form="delete-user-form">Remove</button>
            </div>
        </div>
        </div>
    </div>

    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Users Accounts') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('You can update personal data of each user or remove it.') }}
        </p>
    </header>

    <div class="mt-6 space-y-6 update-user">
        <div>
            <select id="select-bank">
                <option selected disabled>Choose one...</option>
                @foreach ($users as $user)
                    <option value="{{$user->id}}" data-email="{{$user->email}}" data-admin="{{$user->role === config('app.admin_role')}}">{{$user->name}}</option>
                @endforeach
            </select>
        </div>
        <div style="display: flex; gap: 40px;">
            <form class="needs-validation" action="/user/" method="POST" id="update-user-form" data-action="/user/">
                @csrf
                @method('PUT')
                <x-input-label for="username" :value="__('Name')" />
                <input type="text" name="name" id="username" required disabled>
    
                <x-input-label for="useremail" :value="__('Email')"/>
                <input type="email" name="email" id="useremail" required disabled>
    
                <x-input-label for="useradmin" :value="__('Admin')" />
                <input type="checkbox" name="admin" id="useradmin"  disabled/>
    
                <br/>
                <button type="submit" id="update-user-btn" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" style="margin-top: 10px;" disabled>
                    Update
                </button>
    
                <button type="button" data-bs-toggle="modal" data-bs-target="#removeUserModal" id="remove-user-btn" form="delete-user-form" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" style="margin-top: 10px;" disabled>
                    Remove
                </button>
            </form>

            <form class="needs-validation" action="/user/" method="POST" id="update-pass-form" data-action="/user/">
                @csrf
                @method('PUT')
                <x-input-label for="username" :value="__('Password')" />
                <input type="password" name="password" id="userpass" required disabled>
    
                <br/>
                <button type="submit" id="update-pass-btn" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" style="margin-top: 10px;" disabled>
                    Update
                </button>
            </form>
        </div>

    </div>

    <script>

        const formUpdate = document.getElementById("update-user-form");
        const formPass = document.getElementById("update-pass-form");
        const nameInput = document.getElementById("username");
        const passInput = document.getElementById("userpass");
        const emailInput = document.getElementById("useremail");
        const adminCheck = document.getElementById("useradmin");
        const select_user = document.getElementById("select-bank");
        const formRemove = document.getElementById("delete-user-form");

        const btnUpdate = document.getElementById("update-user-btn");
        const btnUpdatePass = document.getElementById("update-pass-btn");
        const btnRemove = document.getElementById("remove-user-btn");

        const spanName = document.querySelector("span.user-name");

        select_user.addEventListener("change", () => {

            nameInput.disabled = !select_user.value;
            emailInput.disabled = !select_user.value;
            adminCheck.disabled = !select_user.value;
            btnUpdate.disabled = !select_user.value;
            btnRemove.disabled = !select_user.value;
            btnUpdatePass.disabled = !select_user.value;
            passInput.disabled = !select_user.value;

            if(!!select_user.value){

                formUpdate.action = formUpdate.dataset.action + select_user.value;
                formRemove.action = formRemove.dataset.action + select_user.value;
                formPass.action = formPass.dataset.action + select_user.value + "/reset_password";

                const userSelected = select_user.options[select_user.selectedIndex];

                spanName.innerText = userSelected.text;
                nameInput.value = userSelected.text;
                emailInput.value = userSelected.dataset.email;
                adminCheck.checked = userSelected.dataset.admin;

            }

        });


    </script>
</section>