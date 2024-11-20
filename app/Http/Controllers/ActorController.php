<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use Illuminate\Http\Request;

class ActorController extends Controller
{
    // Отображение всех актеров
    public function index()
    {
        $actors = Actor::all();
        return response()->json($actors);
    }

    // Отображение формы для создания нового актера
    public function create()
    {
        return view('actors.create');
    }

    // Сохранение нового актера
    public function store(Request $request)
    {
        // Валидация данных актера
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'photo' => 'nullable|string',  // Здесь предполагается, что фото - это строка с URL или путь к файлу
            'biography' => 'nullable|string',
            'movie_ids' => 'required|array', // Массив идентификаторов фильмов
        ]);

        // Создание нового актера
        $actor = Actor::create($validated);

        // Привязка актера к фильмам (если такие фильмы существуют)
        $actor->movies()->attach($request->movie_ids);

        return response()->json([
            'message' => 'Actor created successfully',
            'actor' => $actor,
        ], 201);
    }

    // Отображение одного актера
    public function show($id)
    {
        $actor = Actor::find($id);

        if (!$actor) {
            return response()->json(['message' => 'Actor not found'], 404);
        }
        return response()->json($actor);
    }

    // Отображение формы для редактирования актера
    public function edit($id)
    {
        $actor = Actor::findOrFail($id);
        return view('actors.edit', compact('actor'));
    }

    // Обновление актера
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'bio' => 'nullable|string',
        ]);

        $actor = Actor::findOrFail($id);
        $actor->update($validated);

        return redirect()->route('actors.index');
    }

    // Удаление актера
    public function destroy($id)
    {
        $actor = Actor::find($id);  // Ищем актера по ID

        if (!$actor) {
            return response()->json(['message' => 'Actor not found'], 404);  // Если актера нет, возвращаем 404
        }

        try {
            $actor->delete();  // Пытаемся удалить актера
        } catch (\Exception $e) {
            // В случае ошибки при удалении, возвращаем ошибку 500
            return response()->json(['message' => 'Failed to delete actor', 'error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Actor deleted successfully'], 200);  // Успех
    }
}
