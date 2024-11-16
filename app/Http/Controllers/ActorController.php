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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'bio' => 'nullable|string',
        ]);

        Actor::create($validated);
        return redirect()->route('actors.index');
    }

    // Отображение одного актера
    public function show($id)
    {
        $actor = Actor::findOrFail($id);
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
        $actor = Actor::findOrFail($id);
        $actor->delete();
        return redirect()->route('actors.index');
    }
}
