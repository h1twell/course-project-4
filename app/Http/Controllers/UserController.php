<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Получить всех пользователей
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Получить пользователя по ID
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    // Создать нового пользователя
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return response()->json($user, 201);
    }

    // Обновить данные пользователя
    public function update(Request $request, $id)
    {
       $request->validate([
           'username' => 'nullable|string|min:3|max:255',
           'password' => 'nullable|string|min:6|confirmed',
           'email' => 'nullable|string|email|max:255|unique:users',
           'gender' => 'nullable|string|in:male,female,other',
           'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Поле изображения опционально
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Обновляем поля модели только если они предоставлены в запросе
        if ($request->has('username')) $user->username = $request->username;
        if ($request->has('password')) $user->password = $request->password;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('gender')) $user->gender = $request->gender;

        // Если предоставлено новое изображение, загружаем его
        if ($request->hasFile('avatar')) {
            // Удаляем старое изображение, если оно существует
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Сохраняем новое изображение
            $imagePath = $request->file('avatar')->store('avatar', 'public');
            $user->avatar = $imagePath;
        }

        // Сохраняем изменения в базе данных
        $user->save();

        return response()->json([
            'message' => 'Пользователь успешно обновлен.',
            'user' => $user
        ], 200);
    }

    // Удалить пользователя
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
