@extends('layout/mainlayout')
@section('title', 'Home')

@section('content')

<div class="position-relative">
    <img src="{{ asset('videos/lightleak.gif') }}" class="gif" alt="background home">
    <div class="position-absolute top-50 start-50 translate-middle text-center text-white">
        <h1 class="headerH1">FitRack</h1>
        <h2>The Number 1 Fit Planner</h2>
    </div>
</div>

@endsection
