<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h1 style="font-size: 2rem; font-weight: 600; text-align: center">Some things you can do...</h1>
                <div class="p-6 text-gray-900">
                    <ul style="display: flex; flex-direction: column; justify-content: center; align-items: center">
                        <li><a href="/">Buy products</a></li>
                        <li><a href="/shopping">Go to your cart</a></li>
                        <li><a href="/profile">Edit your personal info</a></li>
                        @if(Auth::user()->role === config('app.admin_role'))
                        <li><a href="/user/control">User Control Panel</a></li>
                        @endif
                        <li><form method="POST" action="/logout">
                            @csrf
                            <a href="/logout" onclick="event.preventDefault();
                                                this.closest('form').submit();">Log Out</a>
                        </form></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
