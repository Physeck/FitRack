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

            <div class="d-flex justify-content-start">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#changeProfilePictureModal">
                    Change Profile Picture
                </button>
            </div>

            <div class="modal fade" id="changeProfilePictureModal" tabindex="-1" aria-labelledby="changeProfilePictureModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changeProfilePictureModalLabel">Change Profile Picture</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="profile_picture" class="form-label">Upload New Profile Picture</label>
                                    <input type="file" name="profile_picture" id="profile_picture" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-success">Update Profile Picture</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>

@endsection
