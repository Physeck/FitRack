@extends('layout.mainlayout')
@section('title', 'Profile')

@section('content')

    <div class="container py-5">
        <div class="d-flex align-items-center pb-5">
            <img src="{{ Auth::user()->profile_picture ?: asset('images/defaultpfp.png') }}"
                alt="Profile Picture" class="rounded-circle"
                style="width: 175px; height: 175px; object-fit: cover; margin-right: 10px; border: 1px solid black">
            <div>
                <h2 class="text-white">{{ Auth::user()->name }}</h2>
            </div>
        </div>
        <div class="container-fluid pb-3 ps-5 pe-5">
            <div class="mb-4 d-flex align-items-center">
                <label for="name" class="form-label text-white me-3" style="width: 5rem">Name</label>
                <div class="border p-2 rounded w-50" style="background-color: #888888;">
                    {{ Auth::user()->name }}
                </div>
            </div>

            <div class="mb-4 d-flex align-items-center">
                <label for="email" class="form-label text-white me-3" style="width: 5rem">Email</label>
                <div class="border p-2 rounded w-50" style="background-color: #888888;">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <form action="{{ route('profile.updateProfilePage') }}" method="POST">
                @csrf
                <div class="mb-4 d-flex align-items-center">
                    <label for="height" class="form-label text-white me-3" style="width: 5rem">Height (Cm)</label>
                    <input type="text" name="height" id="height" class="form-control border p-2 rounded w-50"
                        style="background-color: #fff;" value="{{ old('height', Auth::user()->height) }}">
                </div>

                <div class="mb-4 d-flex align-items-center">
                    <label for="weight" class="form-label text-white me-3" style="width: 5rem">Weight (Kg)</label>
                    <input type="text" name="weight" id="weight" class="form-control border p-2 rounded w-50"
                        style="background-color: #fff;" value="{{ old('weight', Auth::user()->weight) }}">
                </div>


                <div class="d-flex justify-content-start gap-3 p-3">
                    <button class="btn btn-orange" id="updateProfilePage">
                        Update
                    </button>

                    <button type="button" class="btn btn-orange" data-bs-toggle="modal"
                        data-bs-target="#changeProfilePictureModal">
                        Change Profile Picture
                    </button>

                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#verifyPasswordModal">
                        Change Password
                    </button>
                </div>
        </div>
        </form>

        <div class="modal fade" id="changeProfilePictureModal" tabindex="-1"
            aria-labelledby="changeProfilePictureModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changeProfilePictureModalLabel">Change Profile Picture</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('profile.updateProfile') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="profile_picture" class="form-label">Upload New Profile Picture</label>
                                <input type="file" name="profile_picture" id="profile_picture" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-orange">Update Profile Picture</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert-profile">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert-profile">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="modal fade" id="verifyPasswordModal" tabindex="-1" aria-labelledby="verifyPasswordModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verifyPasswordModalLabel">Verify Current Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('profile.verifyPassword') }}" id="verifyPasswordForm">
                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Enter Current Password</label>
                                <input type="password" name="currentPassword" id="currentPassword" class="form-control">
                            </div>
                            <button type="button" class="btn btn-orange" id="verifyPasswordButton">Verify</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="verificationMessage" class="alert" role="alert-password" style="display: none;"></div>


        <!-- Change Password Modal -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="changePasswordForm">
                            @csrf
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">Enter New Password</label>
                                <input type="password" name="newPassword" id="newPassword" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="newPassword_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" name="newPassword_confirmation" id="newPassword_confirmation"
                                    class="form-control" required>
                            </div>
                            <button type="button" class="btn btn-orange" id="submitChangePassword">Change
                                Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="verificationMessage2" class="alert" role="alert-password" style="display: none;"></div>


    </div>

@endsection
