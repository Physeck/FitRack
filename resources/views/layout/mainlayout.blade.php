<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FitRack | @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #424549;">
        <div class="container-fluid">
            <a class="navbar-brand user-select-none" style="color: white;">
                <span style="color: black;">Fit</span><span style="color: whitesmoke;">Rack</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/gymplanning">Workout Plan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/mealplanning">Meal Planning</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/onlinecoaching">Online Trainer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/aboutus">About Us</a>
                    </li>
                </ul>
                <div class="ms-auto">
                    <a class="btn btn-outline-success" href="/signup" role="button">Sign Up</a>
                    <a class="btn btn-outline-secondary" style="" href="/signin">Sign in</a>
                </div>
            </div>
        </div>
    </nav>


    @yield('content')

    @include('layout.footer')

</body>
<script src="{{ asset('js/bootstrap.js') }}"></script>

</html>
