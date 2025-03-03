<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MatchController extends Controller
{
    protected $categoryRepository;
    protected $userRepository;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
    }

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
            
            $matchedUser = $this->userRepository->findById($waitingUser);
            
            return response()->json([
                'message' => 'Match found!',
                'matched_user' => $matchedUser->load('profile'),
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
            'categories' => $this->categoryRepository->getAll(),
        ]);
    }

    public function updateUserCategories(Request $request)
    {
        $request->validate([
            'categories' => ['required', 'array'],
            'categories.*' => ['exists:categories,id'],
        ]);

        $categories = $this->categoryRepository->syncUserCategories(
            $request->user()->id,
            $request->categories
        );

        return response()->json([
            'message' => 'Categories updated successfully',
            'categories' => $categories,
        ]);
    }
}
