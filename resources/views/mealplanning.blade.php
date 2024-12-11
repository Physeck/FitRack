@extends('layout.mainlayout')
@section('title', 'Meal Planning')

@section('content')

<div class="container mt-4">
    <h1 class="mb-4">Your Personalized Meal Planner</h1>
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Weight:</strong> {{ $user->weight }} kg</p>
            <p><strong>Height:</strong> {{ $user->height }} cm</p>
            <p><strong>BMI:</strong> {{ $bmi }} ({{ ucfirst($bmiCategory) }})</p>
            <p><strong>Fitness Goal:</strong> {{ str_replace('_', ' ', ucfirst($fitnessGoal)) }}</p>
        </div>
    </div>

    <!-- Update Meal Preference -->
    <div class="card mb-4">
        <div class="card-header">Your Diet Preference</div>
        <div class="card-body">
            <form method="POST" action="{{ route('update_meal_preference') }}">
                @csrf
                <div class="mb-3">
                    <label for="dietPreference" class="form-label">Choose a Diet Type</label>
                    <select name="diet_preference" class="form-select" id="dietPreference">
                        <option value="balanced" {{ $dietPreference == 'balanced' ? 'selected' : '' }}>Balanced</option>
                        <option value="high_protein" {{ $dietPreference == 'high_protein' ? 'selected' : '' }}>High Protein</option>
                        <option value="vegetarian" {{ $dietPreference == 'vegetarian' ? 'selected' : '' }}>Vegetarian</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Preference</button>
            </form>
            @if(session('status'))
                <div class="alert alert-success mt-3">{{ session('status') }}</div>
            @endif
        </div>
    </div>

    <!-- Personalized Meal Recommendations -->
    <div class="card mb-4">
        <div class="card-header">Your Personalized Nutrition Guidance</div>
        <div class="card-body">
            <p>{{ $recommendations['message'] }}</p>
            <h5>Example Meal Ideas</h5>
            <div class="row">
                @foreach($recommendations['meals'] as $mealType => $mealList)
                    <div class="col-md-3 mb-3">
                        <strong>{{ $mealType }}:</strong>
                        <ul class="list-unstyled mt-2">
                            @foreach($mealList as $meal)
                                <li>- {{ $meal }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
