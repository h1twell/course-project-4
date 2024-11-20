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


// Маршруты для жанров
Route::get('genres', [GenreController::class, 'index']);
Route::get('genres/{id}', [GenreController::class, 'show']);
Route::post('genres', [GenreController::class, 'store']);


// Маршруты для студий
Route::get('studios', [StudioController::class, 'index']);
Route::get('studios/{id}', [StudioController::class, 'show']);
Route::post('studios', [StudioController::class, 'store']);


// Маршруты для актеров
Route::get('actors', [ActorController::class, 'index']);
Route::get('actors/{id}', [ActorController::class, 'show']);
Route::post('actors', [ActorController::class, 'store']);


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
Route::post('users/{id}', [UserController::class, 'update']);
Route::delete('users/{id}', [UserController::class, 'destroy']);
//АДМИН ВСЕГДА ПРАВ
Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
    Route::middleware('auth:sanctum')->post('/logout-all', [AuthController::class, 'logoutFromAllDevices']);

    Route::post('movies/{id}', [MovieController::class, 'update']); // Обновление фильма
    Route::delete('movies/{id}', [MovieController::class, 'destroy']); // Удаление фильма

    Route::post('actors/{id}', [ActorController::class, 'update']);
    Route::delete('actors/{id}', [ActorController::class, 'destroy']);

    Route::post('studios/{id}', [StudioController::class, 'update']);
    Route::delete('studios/{id}', [StudioController::class, 'destroy']);

    Route::post('studios/{id}', [StudioController::class, 'update']);
    Route::delete('studios/{id}', [StudioController::class, 'destroy']);

    Route::post('genres/{id}', [GenreController::class, 'update']);
    Route::delete('genres/{id}', [GenreController::class, 'destroy']);
});
// Регистрация (register)
Route::post('register', [AuthController::class, 'register']);
// Обновление пользователя (updateProfile)
Route::middleware('auth:sanctum')->post('/profile', [AuthController::class, 'updateProfile']);
// Авторизация (login)
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
// Выход со всех устройств (logoutFromAllDevices)


// Выход (лог-аут)
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
