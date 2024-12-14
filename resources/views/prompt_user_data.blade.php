@extends('layout.mainlayout')
@section('title', 'Prompt User Data')

@section('content')
<div class="container mt-4 mb-4 w-50 grid-bg">
    <h1 style="color: #fff">Please Provide Your Details</h1>
    <form action="{{ route('handle_user_data') }}" method="POST" style="color: #fff">
        @csrf
        <input type="hidden" name="redirect" value="{{ $redirect }}">

        <div class="mb-3" >
            <label for="height" class="form-label" >Height (cm)</label>
            <input type="number" name="height" id="height" class="form-control" required min="1" value="{{ old('height') }}">
        </div>

        <div class="mb-3">
            <label for="weight" class="form-label">Weight (kg)</label>
            <input type="number" name="weight" id="weight" class="form-control" required min="1" value="{{ old('weight') }}">
        </div>

        <div class="mb-3">
            <label for="gender" class="form-label">Gender</label>
            <select name="gender" id="gender" class="form-select" required>
                <option value="">Select</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Continue</button>
    </form>
</div>
@endsection
