<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    // Отображение всех жанров
    public function index()
    {
        $genres = Genre::all();
        return response()->json($genres);
    }

    // Отображение формы для создания нового жанра
    public function create()
    {
        return view('genres.create');
    }

    // Сохранение нового жанра
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Genre::create($validated);
        return redirect()->route('genres.index');
    }

    // Отображение одного жанра
    public function show($id)
    {
        $genre = Genre::findOrFail($id);
        return response()->json($genre);
    }

    // Отображение формы для редактирования жанра
    public function edit($id)
    {
        $genre = Genre::findOrFail($id);
        return view('genres.edit', compact('genre'));
    }

    // Обновление жанра
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $genre = Genre::findOrFail($id);
        $genre->update($validated);

        return redirect()->route('genres.index');
    }

    // Удаление жанра
    public function destroy($id)
    {
        $genre = Genre::findOrFail($id);
        $genre->delete();
        return redirect()->route('genres.index');
    }
}
