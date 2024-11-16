<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('movies', [MovieController::class, 'index']); // Список фильмов
Route::get('movies/{id}', [MovieController::class, 'show']); // Получение фильма по ID
Route::post('movies', [MovieController::class, 'store']); // Создание фильма
Route::put('movies/{id}', [MovieController::class, 'update']); // Обновление фильма
Route::delete('movies/{id}', [MovieController::class, 'destroy']); // Удаление фильма

// Маршруты для жанров
Route::get('genres', [GenreController::class, 'index']);
Route::get('genres/{id}', [GenreController::class, 'show']);
Route::post('genres', [GenreController::class, 'store']);
Route::put('genres/{id}', [GenreController::class, 'update']);
Route::delete('genres/{id}', [GenreController::class, 'destroy']);

// Маршруты для студий
Route::get('studios', [StudioController::class, 'index']);
Route::get('studios/{id}', [StudioController::class, 'show']);
Route::post('studios', [StudioController::class, 'store']);
Route::put('studios/{id}', [StudioController::class, 'update']);
Route::delete('studios/{id}', [StudioController::class, 'destroy']);

// Маршруты для актеров
Route::get('actors', [ActorController::class, 'index']);
Route::get('actors/{id}', [ActorController::class, 'show']);
Route::post('actors', [ActorController::class, 'store']);
Route::put('actors/{id}', [ActorController::class, 'update']);
Route::delete('actors/{id}', [ActorController::class, 'destroy']);

// Маршруты для оценок
Route::get('films/{filmId}/ratings', [RatingController::class, 'index']);
Route::post('films/{filmId}/ratings', [RatingController::class, 'store']);

// Маршруты для избранного
Route::get('users/{userId}/favorites', [FavoriteController::class, 'index']);
Route::post('users/{userId}/favorites', [FavoriteController::class, 'store']);
Route::delete('users/{userId}/favorites/{filmId}', [FavoriteController::class, 'destroy']);

// Маршруты для пользователей
Route::get('users', [UserController::class, 'index']);
Route::get('users/{id}', [UserController::class, 'show']);
Route::post('users', [UserController::class, 'store']);
Route::put('users/{id}', [UserController::class, 'update']);
Route::delete('users/{id}', [UserController::class, 'destroy']);

// Регистрация
Route::post('register', [AuthController::class, 'register']);

// Авторизация
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

// Выход (лог-аут)
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
