<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Studio;
use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    // Отображение всех фильмов
    public function index()
    {
        $movies = Movie::all();
        return response()->json($movies);
    }

    // Отображение формы для создания нового фильма
    public function create()
    {
        $genres = Genre::all();
        $studios = Studio::all();
        return view('movies.create', compact('genres', 'studios'));
    }

    // Сохранение нового фильма
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'release_year' => 'required|date_format:Y',
            'duration' => 'required|integer',
            'description' => 'required|string',
            'photo' => 'required|image',
            'studio_id' => 'required|exists:studios,id',
        ]);

        $movie = Movie::create($validated);
        $movie->genres()->sync($request->genres);

        return redirect()->route('movies.index');
    }

    // Отображение одного фильма
    public function show($id)
    {
        $movie = Movie::findOrFail($id);
        return response()->json($movie);
    }

    // Отображение формы для редактирования фильма
    public function edit($id)
    {
        $movie = Movie::findOrFail($id);
        $genres = Genre::all();
        $studios = Studio::all();
        return view('movies.edit', compact('movie', 'genres', 'studios'));
    }

    // Обновление фильма
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'release_year' => 'required|date_format:Y',
            'duration' => 'required|integer',
            'description' => 'required|string',
            'photo' => 'nullable|image',
            'studio_id' => 'required|exists:studios,id',
        ]);

        $movie = Movie::findOrFail($id);
        $movie->update($validated);
        $movie->genres()->sync($request->genres);

        return redirect()->route('movies.index');
    }

    // Удаление фильма
    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);
        $movie->delete();
        return redirect()->route('movies.index');
    }
}
