<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use PDO;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    //
    public function editProfile(Request $request)
    {
        try {
            $request->validate([
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $user = User::find(Auth::id());

            if ($user->profile_picture) {
                if (file_exists(public_path('uploads/' . $user->profile_picture))) {
                    unlink(public_path('uploads/' . $user->profile_picture));
                }
            }

            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);

            $user->profile_picture = $filename;
            $user->save();

            return redirect()->back()->with('success', 'Profile picture updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update profile picture. Please try again.');
        }
    }

    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        if (Hash::check($request->password, Auth::user()->password)) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'newPassword' => 'required|string|min:8',
        ]);

        $user = User::find(Auth::id());
        $user->password = bcrypt($request->newPassword);
        $user->save();

        return response()->json(['status' => 'success']);
    }

    public function updateProfilePage(Request $request)
    {
        try {
            $request->validate([
                'height' => 'nullable|integer|max:250',
                'weight' => 'nullable|integer|max:600',
            ]);

            $user = User::find(Auth::id());
            $user->update([
               'height' => $request->input('height'),
               'weight' => $request->input('weight'),
            ]);

            return redirect()->back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            Log::error('Profile update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Profile update failed!');
        }
    }

    public function showGymPlanner()
{
    $user = User::find(Auth::id());

    $weight = $user->weight; // kg
    $height = $user->height; // cm
    $heightInMeters = $height / 100;
    $bmi = $weight / ($heightInMeters * $heightInMeters);
    $bmi = round($bmi, 1);

    // Determine BMI category
    $bmiCategory = '';
    if ($bmi < 18.5) {
        $bmiCategory = 'underweight';
    } elseif ($bmi < 25) {
        $bmiCategory = 'normal';
    } else {
        $bmiCategory = 'overweight';
    }

    // Retrieve user’s fitness goal if stored, or default to something
    $fitnessGoal = $user->fitness_goal ?? 'maintain_health'; // e.g., 'lose_weight', 'build_muscle', 'maintain_health'

    // Generate personalized recommendations based on BMI category and fitness goal
    $recommendations = $this->generateRecommendations($bmiCategory, $fitnessGoal);

    return view('gymplanning', compact('user', 'bmi', 'bmiCategory', 'fitnessGoal', 'recommendations'));
}

private function generateRecommendations($bmiCategory, $fitnessGoal)
{
    // This is a simplified logic. You can expand as needed.
    $message = '';
    $weeklyPlan = [];

    // Base messages
    if ($bmiCategory === 'underweight') {
        $message = "You’re currently underweight. To improve strength and gain healthy weight, focus on controlled strength training and a slightly higher calorie intake.";
    } elseif ($bmiCategory === 'normal') {
        $message = "You’re at a healthy weight. Maintain a balanced routine of cardio and strength to enhance fitness.";
    } else { // overweight
        $message = "You’re currently above the normal weight range. A combination of moderate cardio and strength training can help you gradually reduce excess weight.";
    }

    // Adjust message and workout plan based on fitness goal
    if ($fitnessGoal === 'lose_weight') {
        $message .= " Since you want to lose weight, prioritize moderate-intensity cardio and a well-structured strength routine that supports fat loss while preserving muscle.";
        $weeklyPlan = [
            'Monday' => '30 mins moderate cardio (jog, bike) + light core work',
            'Tuesday' => 'Upper body strength (light to moderate weights, 8-12 reps)',
            'Thursday' => '30 mins low-impact cardio (brisk walk, elliptical)',
            'Friday' => 'Lower body strength (bodyweight squats, lunges, glute bridges)',
        ];
    } elseif ($fitnessGoal === 'build_muscle') {
        $message .= " Since you want to build muscle, focus on progressive strength training with a balance of compound exercises and adequate rest.";
        $weeklyPlan = [
            'Monday' => 'Upper body strength (bench press, rows, overhead press)',
            'Wednesday' => 'Lower body strength (squats, deadlifts, lunges)',
            'Friday' => 'Full body session (pull-ups, push-ups, hip thrusts)',
        ];
    } else {
        // maintain_health
        $message .= " To maintain your current health, mix moderate cardio and full-body strength workouts evenly.";
        $weeklyPlan = [
            'Tuesday' => 'Light cardio (20-30 mins) + core exercises',
            'Thursday' => 'Full body strength (moderate weights, 8-10 reps)',
            'Saturday' => 'Yoga or pilates for flexibility and balance',
        ];
    }

    return [
        'message' => $message,
        'weeklyPlan' => $weeklyPlan,
    ];
}

public function updateFitnessGoal(Request $request)
    {
        $request->validate([
            'fitness_goal' => 'required|in:lose_weight,build_muscle,maintain_health'
        ]);

        $user = Auth::user();
        $user->fitness_goal = $request->input('fitness_goal');
        $user->save();

        return redirect()->back()->with('status', 'Fitness goal updated successfully!');
    }

    public function showMealPlanner()
    {
        $user = Auth::user();
        $weight = $user->weight;
        $height = $user->height;
        $heightInMeters = $height / 100;
        $bmi = $weight / ($heightInMeters * $heightInMeters);
        $bmi = round($bmi, 1);

        // Determine BMI category
        $bmiCategory = '';
        if ($bmi < 18.5) {
            $bmiCategory = 'underweight';
        } elseif ($bmi < 25) {
            $bmiCategory = 'normal';
        } else {
            $bmiCategory = 'overweight';
        }

        // Assume we have the user's fitness_goal already stored (from previous step)
        $fitnessGoal = $user->fitness_goal ?? 'maintain_health';
        $dietPreference = $user->diet_preference ?? 'balanced';

        // Generate personalized recommendations
        $recommendations = $this->generateMealRecommendations($bmiCategory, $fitnessGoal, $dietPreference);

        return view('mealplanning', compact('user', 'bmi', 'bmiCategory', 'fitnessGoal', 'dietPreference', 'recommendations'));
    }

    private function generateMealRecommendations($bmiCategory, $fitnessGoal, $dietPreference)
    {
        // Base maintenance calories (very rough estimate)
        // Adjust based on BMI category and goals
        $baseCalories = 2000;
        if ($fitnessGoal === 'lose_weight') {
            $calories = $baseCalories - 200; // slight deficit
        } elseif ($fitnessGoal === 'build_muscle') {
            $calories = $baseCalories + 200; // slight surplus
        } else {
            $calories = $baseCalories; // maintenance
        }

        // Adjust macronutrient focus based on diet preference and goals
        // For simplicity:
        // balanced: roughly 30% protein, 40% carbs, 30% fat
        // high_protein: 40% protein, 30% carbs, 30% fat
        // vegetarian: 30% protein (plant-based), 45% carbs, 25% fat
        $macros = [
            'protein' => '30%',
            'carbs' => '40%',
            'fat' => '30%',
        ];

        if ($dietPreference === 'high_protein') {
            $macros['protein'] = '40%';
            $macros['carbs'] = '30%';
            $macros['fat'] = '30%';
        } elseif ($dietPreference === 'vegetarian') {
            $macros['protein'] = '30% (plant-based)';
            $macros['carbs'] = '45%';
            $macros['fat'] = '25%';
        }

        // Suggest simple meals based on diet preference
        $mealSuggestions = $this->getMealSuggestions($dietPreference);

        // Personalized message
        $message = "Based on your BMI category ($bmiCategory) and goal ($fitnessGoal), we recommend about $calories kcal/day.";
        $message .= " Following a $dietPreference approach, aim for roughly {$macros['protein']} protein, {$macros['carbs']} carbs, and {$macros['fat']} fat.";

        return [
            'calories' => $calories,
            'macros' => $macros,
            'message' => $message,
            'meals' => $mealSuggestions
        ];
    }

    private function getMealSuggestions($dietPreference)
    {
        // Basic examples. In reality, you might have a database of recipes.
        if ($dietPreference === 'high_protein') {
            return [
                'Breakfast' => ['Greek yogurt, berries, and whey protein shake', 'Egg white omelet with spinach'],
                'Lunch' => ['Grilled chicken breast, quinoa, broccoli', 'Tuna salad with chickpeas and vegetables'],
                'Dinner' => ['Salmon fillet with sweet potato and asparagus', 'Lean beef stir-fry with peppers and mushrooms'],
                'Snacks' => ['Cottage cheese with pineapple', 'Protein bar or shake']
            ];
        } elseif ($dietPreference === 'vegetarian') {
            return [
                'Breakfast' => ['Oatmeal with almond butter and berries', 'Tofu scramble with spinach and tomatoes'],
                'Lunch' => ['Quinoa bowl with black beans, avocado, and mixed veggies', 'Lentil soup with whole-grain bread'],
                'Dinner' => ['Grilled tempeh with roasted vegetables and brown rice', 'Chickpea curry with spinach and whole-grain naan'],
                'Snacks' => ['Hummus with carrot sticks', 'Apple slices with peanut butter']
            ];
        } else { // balanced
            return [
                'Breakfast' => ['Oatmeal with nuts and berries', 'Scrambled eggs with whole-grain toast'],
                'Lunch' => ['Grilled chicken, sweet potato, mixed veggies', 'Turkey sandwich with avocado and side salad'],
                'Dinner' => ['Salmon with brown rice and steamed broccoli', 'Lean beef stir-fry with bell peppers'],
                'Snacks' => ['Greek yogurt with fruit', 'Apple with almond butter']
            ];
        }
    }

    public function updateMealPreference(Request $request)
    {
        $request->validate([
            'diet_preference' => 'required|in:balanced,high_protein,vegetarian'
        ]);

        $user = Auth::user();
        $user->diet_preference = $request->input('diet_preference');
        $user->save();

        return redirect()->back()->with('status', 'Meal preference updated successfully!');
    }

    public function index(Request $request) {
        $query = $request->input('query', '');
        $category = $request->input('category', '');

        // Here you would use the YouTube Data API client to search videos:
        // For example (pseudo-code):
        // $videos = YoutubeAPI::search($query, $category);

        // Placeholder videos array (replace with actual API response)
        $videos = [
            (object)[
                'title' => 'Full Body Workout for Beginners',
                'thumbnail' => 'https://via.placeholder.com/320x180?text=Video+Thumbnail',
                'videoId' => 'dQw4w9WgXcQ'
            ],
            (object)[
                'title' => '10-Minute Abs Routine',
                'thumbnail' => 'https://via.placeholder.com/320x180?text=Video+Thumbnail',
                'videoId' => 'abcd1234'
            ],
            (object)[
                'title' => '60-Minute Abs Routine',
                'thumbnail' => 'https://via.placeholder.com/320x180?text=Video+Thumbnail',
                'videoId' => 'abcd1234'
            ],
            (object)[
                'title' => 'Full Body Workout for Beginners',
                'thumbnail' => 'https://via.placeholder.com/320x180?text=Video+Thumbnail',
                'videoId' => 'dQw4w9WgXcQ'
            ],
            (object)[
                'title' => '10-Minute Abs Routine',
                'thumbnail' => 'https://via.placeholder.com/320x180?text=Video+Thumbnail',
                'videoId' => 'abcd1234'
            ],
            (object)[
                'title' => '60-Minute Abs Routine',
                'thumbnail' => 'https://via.placeholder.com/320x180?text=Video+Thumbnail',
                'videoId' => 'abcd1234'
            ],
            (object)[
                'title' => 'Full Body Workout for Beginners',
                'thumbnail' => 'https://via.placeholder.com/320x180?text=Video+Thumbnail',
                'videoId' => 'dQw4w9WgXcQ'
            ],
            (object)[
                'title' => '10-Minute Abs Routine',
                'thumbnail' => 'https://via.placeholder.com/320x180?text=Video+Thumbnail',
                'videoId' => 'abcd1234'
            ],
            (object)[
                'title' => '60-Minute Abs Routine',
                'thumbnail' => 'https://via.placeholder.com/320x180?text=Video+Thumbnail',
                'videoId' => 'abcd1234'
            ],
        ];

        return view('onlinecoaching', compact('videos', 'query', 'category'));
    }

    public function searchVideos(Request $request)
    {
        $query = $request->input('q', '');
        $category = $request->input('category', '');
        $pageToken = $request->input('pageToken', '');
        $apiKey = config('app.youtube_api_key');
        $channelId = 'UCeJFgNahi--FKs0oJyeRDEw';

        $url = 'https://www.googleapis.com/youtube/v3/search';

        // Build query params
        $params = [
            'part'       => 'snippet',
            'q'          => $query,
            'type'       => 'video',
            'maxResults' => 9,
            'channelId' => $channelId,
            'key'        => $apiKey,
        ];

        if (!empty($pageToken)) {
            $params['pageToken'] = $pageToken;
        }


// dd($response->status(), $response->body());
// dump($apiKey);

        if (!empty($query)) {
            $params['q'] = $query;
        }
        if (!empty($category)) {
            // If query already exists, append category to it for a more specific search
            // If no query, just use category alone as a search term
            $params['q'] = !empty($query) ? ($query . ' ' . $category) : $category;
        }

        $response = Http::get($url, $params);

        if ($response->failed()) {
            \Log::error('YouTube API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return view('onlinecoaching', [
                'videos' => [],
                'query' => $query,
                'category' => $category,
                'nextPageToken' => null,
                'prevPageToken' => null,
            ]);
        }

        $data = $response->json();

        // Extract tokens for pagination
        $nextPageToken = $data['nextPageToken'] ?? null;
        $prevPageToken = $data['prevPageToken'] ?? null;

        // Transform items
        $videos = [];
        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $videos[] = [
                    'title'       => html_entity_decode($item['snippet']['title']),
                    'thumbnail'   => $item['snippet']['thumbnails']['medium']['url'] ?? '',
                    'videoId'     => $item['id']['videoId'],
                    'description' => html_entity_decode($item['snippet']['description']),
                    'publishedAt' => $item['snippet']['publishedAt']
                ];
            }
        }


        return view('onlinecoaching', [
            'query' => $request->input('query', ''),
            'category' => $request->input('category', ''),
            'videos' => $videos,
            'nextPageToken' => $nextPageToken,
            'prevPageToken' => $prevPageToken,
        ]);
    }
}
