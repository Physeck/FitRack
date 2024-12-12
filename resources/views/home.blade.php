@extends('layout/mainlayout')
@section('title', 'Home')

@section('content')

<div class="position-relative" style="min-height: 96vh; background: url({{ asset('videos/lightleak.gif') }}); background-repeat: no-repeat; background-size: cover;">
    <div class="position-absolute top-50 start-50 translate-middle text-center text-white">
        <h1 class="h1">FitRack</h1>
        <h2 class="h2">The Number 1 Fit Planner</h2>
    </div>
</div>

@endsection
