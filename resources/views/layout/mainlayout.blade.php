<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FitRack | @yield('title')</title>
        {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> --}}
        <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #424549;">
        <div class="container-fluid">
            <a class="navbar-brand" style="color: white;" >
                <span style="color: black;">Fit</span><span style="color: whitesmoke;">Rack</span>
              </a>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="/">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/onlinecoaching">Online Coaching</a>
            </li>
              <li class="nav-item">
                <a class="nav-link" href="/gymplanning">Gym Planning</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/mealplanning">Meal Planning</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/aboutus">About Us</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    @yield('content')
</body>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script> --}}
<script src="{{asset('js/bootstrap.js')}}"></script>
</html>
