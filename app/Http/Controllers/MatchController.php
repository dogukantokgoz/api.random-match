<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MatchController extends Controller
{
    public function findMatch(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $user = $request->user();
        $categoryId = $request->category_id;

        // Store user in waiting list for this category
        $waitingKey = "waiting_list_{$categoryId}";
        
        // Check if there's someone waiting
        $waitingUser = Cache::get($waitingKey);
        
        if ($waitingUser && $waitingUser !== $user->id) {
            // Found a match
            Cache::forget($waitingKey);
            
            return response()->json([
                'message' => 'Match found!',
                'matched_user' => User::with('profile')->find($waitingUser),
            ]);
        }

        // No match found, add user to waiting list
        Cache::put($waitingKey, $user->id, now()->addMinutes(5));

        return response()->json([
            'message' => 'Waiting for match...',
            'waiting' => true,
        ]);
    }

    public function cancelMatch(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $categoryId = $request->category_id;
        $waitingKey = "waiting_list_{$categoryId}";
        
        if (Cache::get($waitingKey) === $request->user()->id) {
            Cache::forget($waitingKey);
        }

        return response()->json([
            'message' => 'Match search cancelled',
        ]);
    }

    public function getCategories()
    {
        return response()->json([
            'categories' => Category::all(),
        ]);
    }

    public function updateUserCategories(Request $request)
    {
        $request->validate([
            'categories' => ['required', 'array'],
            'categories.*' => ['exists:categories,id'],
        ]);

        $user = $request->user();
        $user->categories()->sync($request->categories);

        return response()->json([
            'message' => 'Categories updated successfully',
            'categories' => $user->categories,
        ]);
    }
}
