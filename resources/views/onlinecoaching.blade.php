@extends('layout.mainlayout')
@section('title', 'Online Coaching')

@section('content')

<div class="container mt-4">
    <h1 class="mb-4">Online Trainer</h1>

    <div class="row mb-4">
        <div class="col-12">
            <!-- Search and Filters -->
            <form method="GET" action="{{ route('online_trainer') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-sm-6">
                        <label for="query" class="form-label">Search Videos</label>
                        <input type="text" name="query" class="form-control" id="query" value="{{ $query ?? '' }}" placeholder="e.g., 'Full body workout'">
                    </div>
                    <div class="col-sm-4">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" name="category" id="category">
                            <option value="">All Categories</option>
                            <option value="cardio" {{ (isset($category) && $category == 'cardio') ? 'selected' : '' }}>Cardio</option>
                            <option value="strength" {{ (isset($category) && $category == 'strength') ? 'selected' : '' }}>Strength</option>
                            <option value="yoga" {{ (isset($category) && $category == 'yoga') ? 'selected' : '' }}>Yoga</option>
                            <option value="pilates" {{ (isset($category) && $category == 'pilates') ? 'selected' : '' }}>Pilates</option>
                        </select>
                    </div>
                    <div class="col-sm-2 d-grid">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Section -->
    <div class="row">
        @if(!empty($videos))
            @foreach($videos as $video)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ $video->thumbnail }}" class="card-img-top" alt="Video Thumbnail">
                        <div class="card-body">
                            <h5 class="card-title">{{ $video->title }}</h5>
                            <p class="card-text">A brief description or snippet could go here if available.</p>
                            <a href="https://www.youtube.com/watch?v={{ $video->videoId }}" target="_blank" class="btn btn-secondary">Watch Now</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <p>No videos found. Try adjusting your search or filters.</p>
            </div>
        @endif
    </div>
</div>

@endsection
