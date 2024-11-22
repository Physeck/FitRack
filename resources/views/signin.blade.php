@extends('layout.mainlayout')
@section('title', 'Sign in')

@section('content')

<div class="container-fluid p-0">
    <div class="d-flex">
        <div class="flex-grow-1">
            <img src="{{ asset('images/fitrack-signin-out_img.jpg') }}" class="img-fluid w-100" style="height: 100vh" alt="Sign Up Image">
        </div>

        <div class="bg-light p-5 d-flex flex-column justify-content-center" style="width: 25%;">
            <h2 class="mb-4 text-center">Sign In</h2>
            <form>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter your email">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter your password">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>
                <a href="/" class="btn btn-success w-100">Sign In</a>
            </form>
            <div class="mt-3 text-center">
                <p>Don't have an account? <a href="/signin" style="color: #198754">Sign Up</a></p>
            </div>
        </div>
    </div>
</div>

@endsection
