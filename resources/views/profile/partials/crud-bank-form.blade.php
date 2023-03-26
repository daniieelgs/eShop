<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Account Banks') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('You will need at least one account to make your payments.') }}
        </p>
    </header>

    <div class="mt-6 space-y-6 update-bank">
        <select id="select-bank">
            <option selected disabled>Choose one...</option>
            @foreach ($banks as $bank)
                <option value="{{$bank->id}}">{{$bank->account}}</option>
            @endforeach
        </select>

        <form class="needs-validation" action="/bank/" method="POST" style="display: none;" id="update-bank-form" data-action="/bank/">
            @csrf
            @method('PUT')
            <input type="text" name="account" required>
            <button type="submit" style="background-color: #0843e4; width: 42px; height: 32px;line-height: 15px;"><i class="item_icon save" id="btn_update_form"></i></button>
            <button type="button" class="btn_cancel" id="btn_cancel_form" style="width: 42px; height: 32px; background-color: #444444;line-height: 15px;"><i class="item_icon cancel"></i></button>
        </form>

        <form action="/bank/" method="POST" data-action="/bank/" id="delete-bank-form">
            @csrf
            @method('DELETE')
        </form>

        <i class="item_icon edit" id="edit_bank" style="display: none"></i>
    </div>

    <button type="submit" id="remove-bank-btn" form="delete-bank-form" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" style="margin-bottom: 10px; display: none;">
        Remove
    </button>

    <form action="/bank" method="POST">
        @csrf
        <input type="text" name="account" placeholder="New account bank" required>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Add
        </button>
    </form>

    <script>

        const update_bank_form = document.getElementById("update-bank-form");
        const accountInput = update_bank_form.querySelector('input[name="account"]');
        const select_bank = document.getElementById("select-bank");
        const edit_bank = document.getElementById("edit_bank");
        const btnRemove = document.getElementById("remove-bank-btn");
        const formRemove = document.getElementById("delete-bank-form");

        select_bank.addEventListener("change", () => {

            if(!!select_bank.value) edit_bank.style.display = "";
            else edit_bank.style.display = "none";

        });

        edit_bank.addEventListener("click", e => {

            edit_bank.style.display = "none";
            select_bank.style.display = "none";
            update_bank_form.style.display = "";

            update_bank_form.action = update_bank_form.dataset.action + select_bank.value;

            accountInput.value = select_bank.options[select_bank.selectedIndex].text;

            formRemove.action = formRemove.dataset.action + select_bank.value;

            btnRemove.style.display = "";

        });

        document.getElementById("btn_cancel_form").addEventListener("click", () => {

            edit_bank.style.display = "";
            select_bank.style.display = "";
            update_bank_form.style.display = "none";
            btnRemove.style.display = "none";

        });

    </script>
</section>