<?php

namespace App\Http\Controllers;

use App\Helpers\Common;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user (teacher or student).
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $this->resolveRole($request),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return Common::successResponse('Registration successful', [
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Login user.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return Common::successResponse('Login successful', [
            'user'  => $user,
            'token' => $token,
        ]);
    }

    /**
     * Logout the authenticated user (revoke current token).
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return Common::successResponse('Logged out successfully', []);
    }

    /**
     * Return the authenticated user's info.
     */
    public function me(Request $request): JsonResponse
    {
        return Common::successResponse('User retrieved successfully', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Resolve role from the named route.
     */
    protected function resolveRole(Request $request): string
    {
        if ($request->routeIs('teacher.*')) {
            return Common::TEACHER;
        }

        if ($request->routeIs('student.*')) {
            return Common::STUDENT;
        }

        return Common::STUDENT;
    }
}
