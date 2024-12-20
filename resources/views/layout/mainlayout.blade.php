<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FitRack | @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    {{-- <link rel="stylesheet" href="public/css/bootstrap.css"> --}}
    <link rel="icon" href="{{ asset('images/fitrack_favicon.png') }}" type="image/x-icon">
</head>

<body>
    <div class="min-vh-100">
        <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #424549;">
            <div class="container-fluid">


                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="w-100 d-flex justify-content-between" id="navbarNav">
                    <a class="navbar-brand user-select-none" style="color: white;">
                        <span class="fw-bold" style="color: #f97000;">Fit</span><span style="color: whitesmoke;">Rack</span>
                    </a>
                    <ul class="navbar-nav d-flex justify-content-between fw-bold" style="min-width: 75vw">
                        <li class="nav-item">
                            <a class="nav-link text-center" href="/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center" href="/gymplanning">Workout Plan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center" href="/mealplanning">Meal Planning</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center" href="/onlinecoaching">Online Trainer</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center" href="/aboutus">About Us</a>
                        </li>
                    </ul>
                    <div>
                        @auth
                            <div class="d-flex align-items-center">
                                <span class="navbar-text me-3 text-white">{{ Auth::user()->name }}</span>
                                <a href="/profile">
                                    <img src="{{ Auth::user()->profile_picture ?: asset('images/defaultpfp.png')}}"
                                        alt="Profile Picture" class="rounded-circle"
                                        style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px; border: 1.5px solid #f97000">

                                </a>

                                <a class="btn btn-outline-danger" href="{{ route('signout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('signout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>

                        @endauth

                        @guest
                            <a class="btn btn-outline-orange" href="/signup" role="button">Sign Up</a>
                            <a class="btn btn-outline-success" style="" href="{{ route('login') }}">Sign in</a>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>




        <div style="min-height: 96vh;">
            @yield('content')
        </div>
    </div>
    @include('layout.footer')

</body>
<script src="{{asset('js/script.js')}}"></script>
<script src="{{ asset('js/bootstrap.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.js') }}"></script>


</html>
