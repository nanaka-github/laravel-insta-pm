<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    {{-- Stylesheet/CSS link --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    {{-- Font Awesome CDN link --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                   <h1 class="h5 mb-0"> {{ config('app.name') }}</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    {{-- [SOON] A search bar will be added here. Display only to the login user --}}

                    @auth
                        {{-- ↓　！ = not --}}
                           {{-- = not じゃない場合は表示されない --}}
                        @if (! request()->is('admin/*'))
                            <ul class="navbar-nav ms-auto">
                                <form action="{{route('search')}}" style="width: 300px">
                                    <input type="search" name="search" id="" class="form-control form-control-sm" placeholder="Search...">
                                </form>
                            </ul>
                        @endif
                    @endauth



                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else

                        {{-- Home Button/link --}}
                            {{-- liの中のtitleは、iconがどんな作用をするのかVSC内で自分が把握するため　＝　<img src="picture..." alt="cat image"> ←imgのaltも同じ作用で、アウトプットせずに、VSC内で自分がなんの画像を使っているのか把握するためにaltを使う--}}
                        <li class="nav-item" title="Home">
                           <a href="{{route('index')}}" class="nav-link"><i class="fa-solid fa-house text-dark icon-sm"></i></a>
                        </li>

                        {{-- Create Post Button/link --}}
                        <li class="nav-item" title="Create Post">
                            <a href="{{ route('post.create')}}" class="nav-link"><i class="fa-solid fa-circle-plus text-dark icon-sm"></i></a>
                        </li>

                        {{-- Account Dropdown Button --}}
                        <li class="nav-item dropdown">
                            <button id="account-dropdown" class="btn shadow-none nav-link"  data-bs-toggle="dropdown">
                                @if (Auth::user()->avatar)
                                    <img src="{{Auth::user()->avatar}}" alt="{{ Auth::user()->name }}" class="rounded-circle avatar-sm">
                                @else
                                    <i class="fa-solid fa-circle-user text-dark icon-sm"></i>
                                @endif
                            </button>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="account-dropdown">
                                {{-- ↓ admin <- comes from AppServiceProvider.php--}}
                                @can('admin')
                                {{-- Admin Controls --}}
                                <a href="{{route('admin.users')}}" class="dropdown-item">
                                    <i class="fa-solid fa-user-gear"></i> Admin
                                </a>
                                <hr class="horizontal-divider">
                                @endcan

                                {{-- Profile Button/Link --}}
                                <a href="{{route('profile.show', Auth::user()->id)}}" class="dropdown-item">
                                    <i class="fa-solid fa-circle-user"></i> Profile
                                </a>

                                {{-- Logout Button/Link --}}
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    <i class="fa-solid fa-right-from-bracket"></i> {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>

                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-5">
            <div class="row justify-content-center">
                    {{-- Admin Controllers here --}}
                    {{--
                        When go Login, register, create posts, view records,etc... we are reqesting an HTTp request from our web application

                        Example of an HTTP URI/request:

                        http://Localhost/post/3/show -> is not an admin //line123のrequestに影響する -> admin request

                        http://Localhost/admin/users -> is an admin //userの部分は＊の部分でanything file ＊がないと続きのファイルは表示されない

                        http://Localhost/admin/　-> is not admin

                        http://Localhost/admin/5/update -> is an admin



                        --}}
                @if (request()->is('admin/*'))
                    <div class="col-3">
                        <div class="list-group">
                            <a href="{{ route('admin.users') }}" class="list-group-item {{ request()->is('admin/users') ? 'active':'' }}">
                                {{-- (condition) ? 'true':'false' --}}
                               <i class="fa-solid fa-users"></i> Users
                            </a>
                            <a href="{{ route('admin.posts') }}" class="list-group-item {{ request()->is('admin/posts') ? 'active':''}}">
                                <i class="fa-solid fa-newspaper"></i> Posts
                            </a>
                            <a href="{{route('admin.categories')}}" class="list-group-item {{request()->is('admin/categories') ? 'active':'' }}">
                                <i class="fa-solid fa-tags"></i> Categories
                            </a>
                        </div>
                    </div>
                @endif
                <div class="col-9">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</body>
</html>
