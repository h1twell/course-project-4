<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use Illuminate\Http\Request;

class StudioController extends Controller
{
    // Отображение всех студий
    public function index()
    {
        $studios = Studio::all();
        return response()->json($studios);
    }

    // Отображение формы для создания новой студии
    public function create()
    {
        return view('studios.create');
    }

    // Сохранение новой студии
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Studio::create($validated);
        return redirect()->route('studios.index');
    }

    // Отображение одной студии
    public function show($id)
    {
        $studio = Studio::findOrFail($id);
        return response()->json($studio);
    }

    // Отображение формы для редактирования студии
    public function edit($id)
    {
        $studio = Studio::findOrFail($id);
        return view('studios.edit', compact('studio'));
    }

    // Обновление студии
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $studio = Studio::findOrFail($id);
        $studio->update($validated);

        return redirect()->route('studios.index');
    }

    // Удаление студии
    public function destroy($id)
    {
        $studio = Studio::findOrFail($id);
        $studio->delete();
        return redirect()->route('studios.index');
    }
}
