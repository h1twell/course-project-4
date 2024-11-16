<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    // Отображение всех оценок
    public function index()
    {
        $ratings = Rating::all();
        return response()->json($ratings);
    }

    // Отображение формы для создания новой оценки
    public function create()
    {
        return view('ratings.create');
    }

    // Сохранение новой оценки
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|numeric|min:0|max:10',
            'review_text' => 'required|string',
            'movies_id' => 'required|exists:movies,id',
            'users_id' => 'required|exists:users,id',
        ]);

        Rating::create($validated);
        return redirect()->route('ratings.index');
    }

    // Отображение одной оценки
    public function show($id)
    {
        $rating = Rating::findOrFail($id);
        return response()->json($rating);
    }

    // Отображение формы для редактирования оценки
    public function edit($id)
    {
        $rating = Rating::findOrFail($id);
        return view('ratings.edit', compact('rating'));
    }

    // Обновление оценки
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'rating' => 'required|numeric|min:0|max:10',
            'review_text' => 'required|string',
        ]);

        $rating = Rating::findOrFail($id);
        $rating->update($validated);

        return redirect()->route('ratings.index');
    }

    // Удаление оценки
    public function destroy($id)
    {
        $rating = Rating::findOrFail($id);
        $rating->delete();
        return redirect()->route('ratings.index');
    }
}
