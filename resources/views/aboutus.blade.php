@extends('layout.mainlayout')
@section('title', 'AboutUs')

@section('content')

</div>
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-md-7" style="color: #fff">
                <h1>
                    Welcome to <span class="fw-bold" style="color: #f97000">Fit</span><span class="fw-bold">Rack</span>
                </h1>
                <p class="fs-5">
                    At <span class="highlight" style="color: #f97000">Fit</span><span>Rack</span>, we believe in empowering
                    you
                    to take control of your fitness
                    journey. Our platform is designed to deliver personalized gym and meal plans based on your body metrics,
                    such as
                    <strong>gender</strong>, <strong>weight</strong>, and <strong>height</strong>.
                </p>
                <p class="fs-5">
                    Whether you're aiming to <strong>build strength</strong>, lose weight, or maintain a balanced diet,
                    FitRack tailors everything to help you achieve your goals. We provide <span class="fw-bold">online
                        coaching</span> through expert-led video tutorials, ensuring you get the guidance you need at every
                    stage.
                </p>
            </div>
            <div class="col-md-5 text-end">
                <img src="{{ asset('images/cardiogym.jpg') }}" class="rounded img-fluid w-100" alt="Cardio Gym">
            </div>
        </div>
    </div>


    <div class="bg-light py-5">
        <div class="container text-center">
            <h2 class="fw-semibold text-dark mb-4">What We Offer</h2>
            <div class="row">

                <div class="col-md-4 mb-4">
                    <h3 class="fw-bold" style="color: #f97000">Personalized Workout</h3>
                    <p class="fs-5 text-muted">Custom gym routines to fit your body build.</p>
                </div>

                <div class="col-md-4 mb-4">
                    <h3 class="fw-bold" style="color: #f97000">Meal Plans</h3>
                    <p class="fs-5 text-muted">Various meal picks tailored to your body metrics.</p>
                </div>

                <div class="col-md-4 mb-4">
                    <h3 class="fw-bold" style="color: #f97000">Professional Coaching</h3>
                    <p class="fs-5 text-muted">Learn from certified coaches through expert-led video tutorials.</p>
                </div>
            </div>
        </div>
    </div>
    @guest
    <div class="container py-3">
        <h2 class="fw-bold text-center text-white"> Join Us <a href="/signin"
            class="link-offset-1 link-underline link-underline-opacity-0 join-link"
                style="color: #f97000">NOW</a></h3>
    </div>
    @endguest



@endsection
