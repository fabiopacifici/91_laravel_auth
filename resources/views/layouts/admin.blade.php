<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css' integrity='sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==' crossorigin='anonymous' referrerpolicy='no-referrer' />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Usando Vite -->
    @vite(['resources/js/app.js'])
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="#">BoolPress</a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
            <ul class="navbar-nav px-3">
                <li class="nav-item text-nowrap">
                    <a class="nav-link" href="#">Sign out</a>
                </li>
            </ul>
        </nav>

        <div class="container-fluid vh-100">
            <div class="row h-100">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <div class="position-sticky pt-3">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{Route::currentRouteName() == 'admin.dashboard' ? 'bg-dark' : ''}}" aria-current="page" href="{{route('admin.dashboard')}}">
                                    <i class="fa-solid fa-gauge"></i>
                                    {{__('Dashboard')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{Route::currentRouteName() == 'admin.posts.index' ? 'bg-dark' : ''}}" href="{{route('admin.posts.index')}}">
                                    <i class="fa-solid fa-thumbtack"></i>
                                    {{__('Posts')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="#">
                                    <i class="fa-solid fa-bookmark"></i>
                                    Categories
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="#">
                                    <i class="fa-solid fa-tags"></i>
                                    Tags
                                </a>
                            </li>

                        </ul>
                    </div>
                </nav>

                <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                    @yield('content')
                </main>
            </div>
        </div>

    </div>
</body>

</html>