<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alliance;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Display a listing of people
     */
    public function index()
    {
        $people = Person::with('alliance')->paginate(15);
        return view('admin.people.index', compact('people'));
    }

    /**
     * Show the form for creating a new person
     */
    public function create()
    {
        $alliances = Alliance::orderBy('name')->get();
        return view('admin.people.create', compact('alliances'));
    }

    /**
     * Store a newly created person
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:masculino,femenino,otro',
            'role' => 'required|in:competidor,organizador',
            'alliance_id' => 'nullable|exists:alliances,id',
        ]);

        Person::create($validated);

        return redirect()->route('admin.people.index')
            ->with('success', 'Persona creada exitosamente.');
    }

    /**
     * Show the form for editing the specified person
     */
    public function edit(Person $person)
    {
        $alliances = Alliance::orderBy('name')->get();
        return view('admin.people.edit', compact('person', 'alliances'));
    }

    /**
     * Update the specified person
     */
    public function update(Request $request, Person $person)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:masculino,femenino,otro',
            'role' => 'required|in:competidor,organizador',
            'alliance_id' => 'nullable|exists:alliances,id',
        ]);

        $person->update($validated);

        return redirect()->route('admin.people.index')
            ->with('success', 'Persona actualizada exitosamente.');
    }

    /**
     * Remove the specified person
     */
    public function destroy(Person $person)
    {
        $person->delete();

        return redirect()->route('admin.people.index')
            ->with('success', 'Persona eliminada exitosamente.');
    }
}

