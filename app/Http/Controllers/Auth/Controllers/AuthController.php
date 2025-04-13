<?php

namespace App\Http\Controllers\Auth\Controllers;

use App\Http\Controllers\Auth\Requests\RegisterRequest;
use App\Http\Controllers\Auth\Services\AuthService;
use App\Repositories\Interfaces\UserProfileRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    
    protected $userRepository;
    protected $userProfileRepository;
    protected $authService;
    public function __construct(
        UserRepositoryInterface $userRepository,
        UserProfileRepositoryInterface $userProfileRepository,
        AuthService $authService
    ) {
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $this->authService->register($request);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login credentials',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user->load('profile'),
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
