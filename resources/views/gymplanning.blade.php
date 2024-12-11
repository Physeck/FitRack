@extends('layout.mainlayout')
@section('title', 'Gym Planning')

@section('content')

    <div class="container">
        <h1>Gym Planning Page</h1>
    </div>
<div class="container mt-4">
    <!-- User Data Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-3">Your Fitness Plan</h1>
            <div class="card">
                <div class="card-body">
                    @php
                        // Example calculations
                        $weight = $user->weight ?? 70; // in kg
                        $height = $user->height ?? 175; // in cm
                        $heightInMeters = $height / 100;
                        $bmi = $weight / ($heightInMeters * $heightInMeters);
                        $bmi = round($bmi, 1);
                    @endphp
                    <p><strong>Weight:</strong> {{ $weight }} kg</p>
                    <p><strong>Height:</strong> {{ $height }} cm</p>
                    <p><strong>BMI:</strong> {{ $bmi }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gym Planning Section -->
    <div class="row mb-5">
        <div class="col-md-12">
            <h2 class="mb-3">Gym Planning</h2>
            <div class="card mb-4">
                <div class="card-header">
                    Fitness Goal
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="fitnessGoal" class="form-label">Choose a Goal</label>
                            <select class="form-select" id="fitnessGoal">
                                <option value="lose_weight">Lose Weight</option>
                                <option value="build_muscle">Build Muscle</option>
                                <option value="maintain_health">Maintain Health</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary">Update Goal</button>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    Suggested Weekly Workout
                </div>
                <div class="card-body">
                    <p>Here’s a simple weekly schedule to get you started:</p>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Monday:</strong> Light Cardio (Brisk walking or cycling for 20 mins)</li>
                        <li class="list-group-item"><strong>Wednesday:</strong> Upper Body (Push-ups, light dumbbell presses)</li>
                        <li class="list-group-item"><strong>Friday:</strong> Lower Body (Squats, lunges, light leg presses)</li>
                        <li class="list-group-item"><strong>Weekend:</strong> Optional - Gentle Yoga or Stretching</li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    Track Your Progress
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="weeklyWeight" class="form-label">Log Your Weight (kg)</label>
                            <input type="number" class="form-control" id="weeklyWeight" placeholder="e.g., 69.5">
                        </div>
                        <button type="button" class="btn btn-success">Update Progress</button>
                    </form>
                    <!-- In a real app, you'd show a chart or a list of previous logs -->
                    <p class="mt-3"><em>Progress chart or history can go here.</em></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Meal Planner Section -->
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-3">Meal Planner</h2>
            <div class="card">
                <div class="card-header">
                    Daily Calorie Estimate
                </div>
                <div class="card-body">
                    @php
                        // A very rough estimate of maintenance calories (Mifflin-St Jeor or a simple formula could be used, here just a placeholder)
                        $estimatedCalories = 2000;
                    @endphp
                    <p>Based on your height and weight, a simple estimate of daily maintenance calories is around <strong>{{ $estimatedCalories }} kcal</strong>.</p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    Simple Meal Recommendations
                </div>
                <div class="card-body">
                    <h5>Guidelines</h5>
                    <p>Aim for a balanced plate with a portion of protein, complex carbohydrates, and vegetables.</p>

                    <h5 class="mt-4">Sample Meals</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Breakfast:</strong>
                            <ul class="list-unstyled">
                                <li>Oatmeal with berries</li>
                                <li>Greek yogurt with honey and nuts</li>
                            </ul>
                            <strong>Lunch:</strong>
                            <ul class="list-unstyled">
                                <li>Grilled chicken with quinoa and mixed veggies</li>
                                <li>Turkey sandwich with a side salad</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <strong>Dinner:</strong>
                            <ul class="list-unstyled">
                                <li>Salmon with brown rice and broccoli</li>
                                <li>Lean beef stir-fry with bell peppers and mushrooms</li>
                            </ul>
                            <strong>Snacks:</strong>
                            <ul class="list-unstyled">
                                <li>Carrot sticks with hummus</li>
                                <li>Apple slices with a tablespoon of peanut butter</li>
                            </ul>
                        </div>
                    </div>

                    <h5 class="mt-4">Portion Suggestions</h5>
                    <p>Try using hand-based measurements: a palm-sized portion of protein, a fist of vegetables, a cupped hand of carbs.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
