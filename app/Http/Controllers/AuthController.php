<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Логин пользователя
    public function login(Request $request)
    {
        // Валидация входных данных
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Поиск пользователя
        $user = User::where('email', $validated['email'])->first();
        if (!$user) {
            dd('User not found');
        }

        // Проверка пароля
        if (!Hash::check($validated['password'], $user->password)) {
            dd('Invalid password');
        }

        // Генерация токена
        try {
            $token = $user->createToken('api_token')->plainTextToken;
        } catch (\Exception $e) {
            dd('Token generation failed: ' . $e->getMessage());
        }

        // Успешный ответ
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'gender' => $user->gender,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    // Логик выхода
    public function logout(Request $request)
    {
        // Удаление токена
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
    public function register(Request $request)
    {
        // Валидация входных данных
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed', // Для password_confirmation
            'gender' => 'nullable|string|in:male,female,other', // Допускаем NULL или ограниченные значения
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Допускаем NULL
        ]);
        $imagePath = $request->hasFile('avatar')
            ? $request->file('avatar')->store('avatar', 'public')
            : null;

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Создание пользователя
        $user = User::create([
            'roles_id' => 2, // Роль "user" по умолчанию
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Хэширование пароля
            'gender' => $request->gender, // Поле необязательно
            'avatar' => $imagePath,
        ]);

        // Создание токена
        $token = $user->createToken('YourAppName')->plainTextToken;

        // Ответ с успешной регистрацией
        return response()->json([
            'message' => 'Регистрация прошла успешно!',
            'user' => $user,
            'token' => $token,
        ], 201);
    }
    public function updateProfile(Request $request)
    {
        // Получение текущего пользователя
        $user = $request->user();

        // Валидация входных данных
        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|string|max:255|unique:users,username,' . $user->id, // Проверка уникальности
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id, // Проверка уникальности
            'gender' => 'nullable|string|in:male,female,other', // Допускаем NULL или ограниченные значения
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Допускаем NULL
            'password' => 'nullable|string|min:6|confirmed', // Проверка подтверждения пароля
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Обновление данных пользователя
        $user->update($request->only([
            'username',
            'email',
            'gender',
            'avatar',
        ]));

        // Обновление пароля, если предоставлен
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Ответ об успешном обновлении
        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }
    public function logoutFromAllDevices(Request $request)
    {
        // Получаем текущего пользователя
        $user = $request->user();

        // Удаляем все токены пользователя
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Logged out from all devices successfully'
        ], 200);
    }
}
