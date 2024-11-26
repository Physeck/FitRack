@extends('layout.mainlayout')
@section('title', 'Profile')

@section('content')

    <div class="container" style="padding: 0 1rem 0 1rem">
        <div class="d-flex align-items-center pb-5">
            <img src="{{ Auth::user()->profile_picture ?? asset('images/defaultpfp.png') }}" alt="Profile Picture"
                class="rounded-circle"
                style="width: 175px; height: 175px; object-fit: cover; margin-right: 10px; border: 1px solid black">
            <div>
                <h2 class="text-white">{{ Auth::user()->name }}</h2>
            </div>
        </div>
        <div class="container-fluid p-3">
            <div class="mb-3 d-flex align-items-center">
                <label for="name" class="form-label text-white me-3" style="width: 5rem">Name</label>
                <div class="border p-2 rounded w-50" style="background-color: #f8f9fa;">
                    {{ Auth::user()->name }}
                </div>
            </div>

            <div class="mb-3 d-flex align-items-center">
                <label for="email" class="form-label text-white me-3" style="width: 5rem">Email</label>
                <div class="border p-2 rounded w-50" style="background-color: #f8f9fa;">
                    {{ Auth::user()->email }}
                </div>
            </div>

            {{--
                <div class="mb-3 d-flex align-items-center">
                    <label for="name" class="form-label text-white me-3" style="width: 80px;">Name:</label>
                    <div class="border p-2 rounded" style="background-color: #f8f9fa;">
                        {{ Auth::user()->name }}
                    </div>
                </div>

                <!-- Email Row -->
                <div class="mb-3 d-flex align-items-center">
                    <label for="email" class="form-label text-white me-3" style="width: 80px;">Email:</label>
                    <div class="border p-2 rounded" style="background-color: #f8f9fa;">
                        {{ Auth::user()->email }}
                    </div>
                </div>
            --}}
        </div>
    </div>

@endsection
