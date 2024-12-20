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
use DateInterval;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function editProfile(Request $request){
        try {
            // Validate the uploaded file
            $request->validate([
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
            $user = User::find(Auth::id());
            if ($user->profile_picture) {
                // Extract the file path from the profile picture URL
                $oldFilePath = parse_url($user->profile_picture, PHP_URL_PATH);
                $oldFilePath = ltrim($oldFilePath, '/'); // Remove leading slash if present

                // Delete the old profile picture from S3
                if (Storage::disk('s3')->exists($oldFilePath)) {
                    Storage::disk('s3')->delete($oldFilePath);
                }
            }

            // Get the uploaded file
            $file = $request->file('profile_picture');

            // Generate a unique file name
            $fileName = 'profile_pictures/' . time() . '_' . $file->getClientOriginalName();

            // Upload the new profile picture to S3
            $result = Storage::disk('s3')->put($fileName, file_get_contents($file->getRealPath()), 'public');
            if (!$result) {
                return redirect()->back()->with('error','Failed to upload the file to S3.');
            }

            // Get the public URL for the uploaded file
            $url = Storage::disk('s3')->url($fileName);

            // Save the new profile picture URL to the user's profile
            $user->profile_picture = $url;
            $user->save();
            return redirect()->back()->with('success', 'Profile picture updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update profile picture. Please try again. Error: ' . $e->getMessage());
        }
    }

    public function verifyPassword(Request $request){
        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        if (Hash::check($request->password, Auth::user()->password)) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

    public function updatePassword(Request $request){
        $request->validate([
            'newPassword' => 'required|string|min:8',
        ]);

        $user = User::find(Auth::id());
        $user->password = bcrypt($request->newPassword);
        $user->save();

        return response()->json(['status' => 'success']);
    }

    public function updateProfilePage(Request $request){
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

    public function showForm(Request $request){
        // If logged in and user already has data, redirect to original page
        $user = User::find(Auth::id());
        if ($user) {
            if ($user->height > 0 && $user->weight > 0 && $user->gender) {
                return redirect()->intended('gymplanning');
            }
        }

        return view('prompt_user_data', [
            'redirect' => $request->input('redirect', 'gymplanning'),
        ]);
    }

    public function handleForm(Request $request){
        $request->validate([
            'height' => 'required|numeric|min:1',
            'weight' => 'required|numeric|min:1',
            'gender' => 'required|in:male,female',
        ]);

        $height = $request->input('height');
        $weight = $request->input('weight');
        $gender = $request->input('gender');
        $redirect = $request->input('redirect', 'gymplanning');
        $user = User::find(Auth::id());
        if ($user) {
            // User is logged in, directly update their info
            $user->height = $height;
            $user->weight = $weight;
            $user->gender = $gender;
            $user->save();
            return redirect()->route($redirect);
        } else {
            // User not logged in, store in session and then redirect to register page
            session([
                'temp_height' => $height,
                'temp_weight' => $weight,
                'temp_gender' => $gender,
                'intended_redirect' => $redirect,

            ]);

            return redirect()->route('signup')->with('status', 'Please register or log in to save your data.');
        }

    }

    public function showGymPlanner(){
        $user = User::find(Auth::id());

        $weight = $user->weight; // kg
        $height = $user->height; // cm
        $heightInMeters = $height / 100;
        $bmi = $weight / ($heightInMeters * $heightInMeters);
        $bmi = round($bmi, 1);

        // Determine BMI category
        $bmiCategory = $this->determineBMICategory($bmi);

        // Retrieve user’s fitness goal if stored
        $fitnessGoal = $user->fitness_goal ?? 'maintain_health';

        // Generate personalized recommendations based on BMI category and fitness goal
        $recommendations = $this->generateRecommendations($bmiCategory, $fitnessGoal);

        return view('gymplanning', compact('user', 'bmi', 'bmiCategory', 'fitnessGoal', 'recommendations'));
    }

    private function generateRecommendations($bmiCategory, $fitnessGoal){
        $message = '';
        $weeklyPlan = [];

        // Base messages
        if ($bmiCategory === 'underweight') {
            $message = 'You’re currently underweight. To improve strength and gain healthy weight, focus on controlled strength training and a slightly higher calorie intake.';
        } elseif ($bmiCategory === 'normal') {
            $message = 'You’re at a healthy weight. Maintain a balanced routine of cardio and strength to enhance fitness.';
        } else {
            // overweight
            $message = 'You’re currently above the normal weight range. A combination of moderate cardio and strength training can help you gradually reduce excess weight.';
        }

        if ($fitnessGoal === 'lose_weight') {
            $message .= ' Since you want to lose weight, prioritize moderate-intensity cardio and a well-structured strength routine that supports fat loss while preserving muscle.';
            $weeklyPlan = [
            'Monday' => '30 mins moderate cardio (jog, bike) + light core work',
            'Tuesday' => 'Upper body strength (light to moderate weights, 8-12 reps)',
            'Wednesday' => 'Yoga or active recovery (light stretching, short walk)',
            'Thursday' => '30 mins low-impact cardio (brisk walk, elliptical)',
            'Friday' => 'Lower body strength (bodyweight squats, lunges, glute bridges)',
            'Saturday' => 'Moderate cardio (bike or jog) + mobility drills',
            'Sunday' => 'Rest day (light stretching or a leisure walk)',
            ];
        } elseif ($fitnessGoal === 'build_muscle') {
            $message .= ' Since you want to build muscle, focus on progressive strength training with a balance of compound exercises and adequate rest.';
            $weeklyPlan = [
                'Monday' => 'Upper body strength (bench press, rows, overhead press)',
            'Tuesday' => 'Light cardio or active recovery (short walk or easy cycling)',
            'Wednesday' => 'Lower body strength (squats, deadlifts, lunges)',
            'Thursday' => 'Core and accessory work (pull-ups, push-ups, hip thrusts)',
            'Friday' => 'Full body strength session (compound lifts, moderate reps)',
            'Saturday' => 'Light active recovery (yoga or mobility work)',
            'Sunday' => 'Rest day',
            ];
        } else {
            // maintain_health
            $message .= ' To maintain your current health, mix moderate cardio and full-body strength workouts evenly.';
            $weeklyPlan = [
                'Monday' => 'Light cardio (20-30 mins) + core exercises',
                'Tuesday' => 'Full body strength (moderate weights, 8-10 reps)',
                'Wednesday' => 'Yoga or pilates for flexibility and balance',
                'Thursday' => 'Moderate cardio (jog or cycle)',
                'Friday' => 'Upper body strength (moderate weights)',
                'Saturday' => 'Lower body strength + core work',
                'Sunday' => 'Rest day (leisure walk or gentle stretching)',
            ];
        }

        return [
            'message' => $message,
            'weeklyPlan' => $weeklyPlan,
        ];
    }

    public function updateFitnessGoal(Request $request){
        $request->validate([
            'fitness_goal' => 'required|in:lose_weight,build_muscle,maintain_health',
        ]);

        $user = User::find(Auth::id());
        $user->fitness_goal = $request->input('fitness_goal');
        $user->save();

        return redirect()->back()->with('status', 'Fitness goal updated successfully!');
    }

    public function calculateCalories(){
        $user = User::find(Auth::id());

        // Ensure user data is available
        if (!$user->weight || !$user->height || !$user->gender) {
            return redirect()->route('prompt_user_data')->with('error', 'Please complete your profile to calculate calories.');
        }

        // Calculate BMR
        $bmr = 0;
        if ($user->gender === 'male') {
            $bmr = (10 * $user->weight) + (6.25 * $user->height) + 5;
        } elseif ($user->gender === 'female') {
            $bmr = (10 * $user->weight) + (6.25 * $user->height) - 161;
        }

        $bmi = $user->weight / pow($user->height / 100, 2);
        $bmiCategory = $this->determineBMICategory($bmi);

        if ($bmiCategory === 'underweight') {
            $bmr += 300; // Increase calories for underweight
        } elseif ($bmiCategory === 'overweight') {
            $bmr -= 300; // Decrease calories for overweight
        }

        // Adjust for fitness goal
        $calories = $bmr;
        if ($user->fitness_goal === 'lose_weight') {
            $calories -= 200; // Create a calorie deficit
        } elseif ($user->fitness_goal === 'build_muscle') {
            $calories += 200; // Create a calorie surplus
        }

        return round($calories); // Return the final calorie target
    }

    private function determineBMICategory($bmi){
        if ($bmi < 18.5) {
            return 'underweight';
        } elseif ($bmi >= 18.5 && $bmi < 25) {
            return 'normal';
        } else {
            return 'overweight';
        }
    }

    public function showMealPlanner(){
        $user = User::find(Auth::id());
        $weight = $user->weight;
        $height = $user->height;
        $heightInMeters = $height / 100;
        $bmi = $weight / ($heightInMeters * $heightInMeters);
        $bmi = round($bmi, 1);

        // Determine BMI category
        $bmiCategory = $this->determineBMICategory($bmi);

        $fitnessGoal = $user->fitness_goal ?? 'maintain_health';
        $dietPreference = $user->diet_preference ?? 'balanced';

        // Generate personalized recommendations
        $recommendations = $this->generateMealRecommendations($bmiCategory, $fitnessGoal, $dietPreference);

        return view('mealplanning', compact('user', 'bmi', 'bmiCategory', 'fitnessGoal', 'dietPreference', 'recommendations'));
    }

    private function generateMealRecommendations($bmiCategory, $fitnessGoal, $dietPreference){
        $calories = $this->calculateCalories();

        // macronutrient
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

        // Suggest smeals based on diet preference
        $mealSuggestions = $this->getMealSuggestions($dietPreference);

        // Personalized message
        $message = "Based on your BMI category ($bmiCategory) and goal ($fitnessGoal), we recommend about $calories kcal/day.";
        $message .= " Following a $dietPreference approach, aim for roughly {$macros['protein']} protein, {$macros['carbs']} carbs, and {$macros['fat']} fat.";

        return [
            'calories' => $calories,
            'macros' => $macros,
            'message' => $message,
            'meals' => $mealSuggestions,
        ];
    }

    private function getMealSuggestions($dietPreference){
        if ($dietPreference === 'high_protein') {
            return [
                'Breakfast' => ['Greek yogurt, berries, and whey protein shake', 'Egg white omelet with spinach'],
                'Lunch' => ['Grilled chicken breast, quinoa, broccoli', 'Tuna salad with chickpeas and vegetables'],
                'Dinner' => ['Salmon fillet with sweet potato and asparagus', 'Lean beef stir-fry with peppers and mushrooms'],
                'Snacks' => ['Cottage cheese with pineapple', 'Protein bar or shake'],
            ];
        } elseif ($dietPreference === 'vegetarian') {
            return [
                'Breakfast' => ['Oatmeal with almond butter and berries', 'Tofu scramble with spinach and tomatoes'],
                'Lunch' => ['Quinoa bowl with black beans, avocado, and mixed veggies', 'Lentil soup with whole-grain bread'],
                'Dinner' => ['Grilled tempeh with roasted vegetables and brown rice', 'Chickpea curry with spinach and whole-grain naan'],
                'Snacks' => ['Hummus with carrot sticks', 'Apple slices with peanut butter'],
            ];
        } else {
            // balanced
            return [
                'Breakfast' => ['Oatmeal with nuts and berries', 'Scrambled eggs with whole-grain toast'],
                'Lunch' => ['Grilled chicken, sweet potato, mixed veggies', 'Turkey sandwich with avocado and side salad'],
                'Dinner' => ['Salmon with brown rice and steamed broccoli', 'Lean beef stir-fry with bell peppers'],
                'Snacks' => ['Greek yogurt with fruit', 'Apple with almond butter'],
            ];
        }
    }

    public function updateMealPreference(Request $request){
        $request->validate([
            'diet_preference' => 'required|in:balanced,high_protein,vegetarian',
        ]);

        $user = User::find(Auth::id());
        $user->diet_preference = $request->input('diet_preference');
        $user->save();

        return redirect()->back()->with('status', 'Meal preference updated successfully!');
    }

    public function searchVideos(Request $request){
        $query = $request->input('query', '');
        $category = $request->input('category', '');
        $pageToken = $request->input('pageToken', '');
        $apiKey = config('app.youtube_api_key');
        $channelId = 'UCeJFgNahi--FKs0oJyeRDEw';

        $url = 'https://www.googleapis.com/youtube/v3/search';
        // Build query params
        $params = [
            'part' => 'snippet',
            'q' => $query,
            'type' => 'video',
            'channelId' => $channelId,
            'key' => $apiKey,
            'maxResults' => 9,
            'videoDuration' => 'medium',
            'order' => 'relevance',
        ];

        if (!empty($pageToken)) {
            $params['pageToken'] = $pageToken;
        }

        if (!empty($query)) {
            $params['q'] = $query;
        }
        if (!empty($category)) {
            // If query already exists, append category to it
            // If no query, just use category alone as a search term
            $params['q'] = !empty($query) ? $query . ' ' . $category : $category;
        }
        // dump($category);

        $searchResponse = Http::get('https://www.googleapis.com/youtube/v3/search', $params);

        if ($searchResponse->failed()) {
            Log::error('YouTube API Error', [
                'status' => $searchResponse->status(),
                'body' => $searchResponse->body(),
            ]);
            return view('onlinecoaching', [
                'videos' => [],
                'query' => $query,
                'category' => $category,
                'nextPageToken' => null,
                'prevPageToken' => null,
            ]);
        }

        $data = $searchResponse->json();

        $nextPageToken = $data['nextPageToken'] ?? null;
        $prevPageToken = $data['prevPageToken'] ?? null;

        $videoIds = [];
        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $videoIds[] = $item['id']['videoId'];
            }
        }

        if (empty($videoIds)) {
            // No videos found, just return empty
            return view('onlinecoaching', [
                'videos' => [],
                'query' => $query,
                'category' => $category,
                'nextPageToken' => $nextPageToken,
                'prevPageToken' => $prevPageToken,
            ]);
        }

        // Fetch video details
        $videosParams = [
            'part' => 'snippet,contentDetails',
            'id' => implode(',', $videoIds),
            'key' => $apiKey,
        ];

        $videosResponse = Http::get('https://www.googleapis.com/youtube/v3/videos', $videosParams);
        if ($videosResponse->failed()) {
            Log::error('YouTube API Error (videos)', [
                'status' => $videosResponse->status(),
                'body' => $videosResponse->body(),
            ]);
            return view('onlinecoaching', [
                'videos' => [],
                'query' => $query,
                'category' => $category,
                'nextPageToken' => $nextPageToken,
                'prevPageToken' => $prevPageToken,
            ]);
        }

        $videosData = $videosResponse->json();

        $filteredVideos = [];
        if (isset($videosData['items'])) {
            foreach ($videosData['items'] as $videoItem) {
                $filteredVideos[] = [
                    'title' => html_entity_decode($videoItem['snippet']['title']),
                    'thumbnail' => $videoItem['snippet']['thumbnails']['medium']['url'] ?? '',
                    'videoId' => $videoItem['id'],
                    'description' => Str::limit(html_entity_decode($videoItem['snippet']['description']), 100, '...'),
                    'publishedAt' => $videoItem['snippet']['publishedAt'],
                ];
            }
        }

        $filteredVideos = array_slice($filteredVideos, 0, 9);
        return view('onlinecoaching', [
            'query' => $request->input('query', ''),
            'category' => $request->input('category', ''),
            'videos' => $filteredVideos,
            'nextPageToken' => $nextPageToken,
            'prevPageToken' => $prevPageToken,
        ]);
    }
}
