<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // Получить все фильмы в избранном пользователя
    public function index($userId)
    {
        $favorites = Favorite::where('user_id', $userId)->get();
        return response()->json($favorites);
    }

    // Добавить фильм в избранное
    public function store(Request $request, $userId)
    {
        $data = $request->validate([
            'film_id' => 'required|integer',
        ]);

        $data['user_id'] = $userId;

        $favorite = Favorite::create($data);
        return response()->json($favorite, 201);
    }

    // Удалить фильм из избранного
    public function destroy($userId, $filmId)
    {
        $favorite = Favorite::where('user_id', $userId)->where('film_id', $filmId)->first();

        if (!$favorite) {
            return response()->json(['message' => 'Favorite not found'], 404);
        }

        $favorite->delete();
        return response()->json(['message' => 'Favorite removed successfully']);
    }
}
