@extends('layout.mainlayout')
@section('title', 'Sign up')

@section('content')

<div class="container-fluid p-0">
    <div class="d-flex">
        <div class="flex-grow-1">
            <img src="{{ asset('images/fitrack-signin-out_img.jpg') }}" class="img-fluid w-100" style="height: 100vh" alt="Sign Up Image">
        </div>

        <div class="bg-light p-5 d-flex flex-column justify-content-center" style="width: 25%;">
            <h2 class="mb-4 text-center">Sign Up</h2>
            <form action="{{route('signup.post')}}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter your name">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password">
                </div>
                <div class="mb-3">
                    <label for="confPassword" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" id="confPassword" placeholder="Enter your password again">
                </div>
                <button class="btn btn-success w-100">Sign Up</button>
            </form>
            <div class="mt-3 text-center">
                <p>Already have an account? <a href="/signin" style="color: #198754">Sign In</a></p>
            </div>
        </div>
    </div>
</div>

@endsection
