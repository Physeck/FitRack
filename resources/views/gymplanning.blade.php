@extends('layout.mainlayout')
@section('title', 'Gym Planning')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4 text-white">Workout Planner</h1>
        <div class="row mb-4">
            <div class="col-md-12">
                <!-- User Info & BMI -->
                <div class="card mb-3" style="background-color: rgba(255, 255, 255, 0.75) !important;">
                    <div class="card-body">
                        <p><strong>Weight:</strong> {{ $user->weight }} kg</p>
                        <p><strong>Height:</strong> {{ $user->height }} cm</p>
                        <p><strong>BMI:</strong> {{ $bmi }} ({{ ucfirst($bmiCategory) }})</p>
                    </div>
                </div>

                <!-- Fitness Goal Selection -->
                <div class="card mb-3" style="background-color: rgba(255, 255, 255, 0.75) !important;">
                    <div class="card-header">Your Fitness Goal</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('update_fitness_goal') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="fitnessGoal" class="form-label">Choose a Goal</label>
                                <select name="fitness_goal" class="form-select" id="fitnessGoal" style="background-color: rgba(255, 255, 255, 0.75) !important;">
                                    <option value="lose_weight" {{ $fitnessGoal == 'lose_weight' ? 'selected' : '' }}>Lose
                                        Weight</option>
                                    <option value="build_muscle" {{ $fitnessGoal == 'build_muscle' ? 'selected' : '' }}>
                                        Build Muscle</option>
                                    <option value="maintain_health"
                                        {{ $fitnessGoal == 'maintain_health' ? 'selected' : '' }}>Maintain Health</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-orange">Update Goal</button>
                        </form>

                        @if (session('status'))
                            <div class="alert alert-success mt-3">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Personalized Recommendations -->
                <div class="card mb-4" style="background-color: rgba(255, 255, 255, 0.75) !important;">
                    <div class="card-header">Personalized Advice</div>
                    <div class="card-body">
                        <p>{{ $recommendations['message'] }}</p>
                    </div>
                </div>

                <!-- Weekly Plan -->
                <div class="card" style="background-color: rgba(255, 255, 255, 0.75) !important;">
                    <div class="card-header">Your Weekly Workout Plan</div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($recommendations['weeklyPlan'] as $day => $plan)
                                <li class="list-group-item" style="background-color: rgba(255, 255, 255, 0.75) !important;">
                                    <strong>{{ $day }}:</strong> {{ $plan }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
