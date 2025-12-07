<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskApplication;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ako je nastavnik, prikazuje samo svoje radove
        if (auth()->user()->isNastavnik()) {
            $tasks = Task::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // Admin i student vide sve radove
            $tasks = Task::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Samo nastavnik može kreirati radove
        if (!auth()->user()->isNastavnik()) {
            abort(403, 'Samo nastavnici mogu dodavati radove.');
        }
        
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Samo nastavnik može kreirati radove
        if (!auth()->user()->isNastavnik()) {
            abort(403, 'Samo nastavnici mogu dodavati radove.');
        }

        $validated = $request->validate([
            'naziv_rada' => 'required|string|max:255',
            'naziv_rada_eng' => 'required|string|max:255',
            'zadatak_rada' => 'required|string',
            'tip_studija' => 'required|in:stručni,preddiplomski,diplomski',
        ]);

        $validated['user_id'] = auth()->id();

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Rad je uspješno dodan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        // Samo vlasnik (nastavnik) može uređivati svoj rad
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Možete uređivati samo svoje radove.');
        }
        
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        // Samo vlasnik (nastavnik) može uređivati svoj rad
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Možete uređivati samo svoje radove.');
        }

        $validated = $request->validate([
            'naziv_rada' => 'required|string|max:255',
            'naziv_rada_eng' => 'required|string|max:255',
            'zadatak_rada' => 'required|string',
            'tip_studija' => 'required|in:stručni,preddiplomski,diplomski',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Rad je uspješno ažuriran.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        // Samo vlasnik (nastavnik) može obrisati svoj rad
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Možete obrisati samo svoje radove.');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Rad je uspješno obrisan.');
    }

    /**
     * Student applies to a task.
     */
    public function apply(Request $request, Task $task)
    {
        // Samo studenti mogu se prijaviti
        if (!auth()->user()->isStudent()) {
            abort(403, 'Samo studenti se mogu prijaviti na radove.');
        }

        // Provjera da li se već prijavio
        if ($task->hasApplied(auth()->id())) {
            return redirect()->back()->with('error', __('tasks.Already applied to this task'));
        }

        // Provjera da li je dostigao limit od 5 prijava
        $currentApplicationsCount = TaskApplication::where('user_id', auth()->id())->count();
        if ($currentApplicationsCount >= 5) {
            return redirect()->back()->with('error', __('tasks.Maximum applications reached'));
        }

        // Validacija prioriteta
        $validated = $request->validate([
            'priority' => 'required|integer|min:1|max:5',
        ]);

        // Provjera da li prioritet već postoji
        $priorityExists = TaskApplication::where('user_id', auth()->id())
            ->where('priority', $validated['priority'])
            ->exists();
        
        if ($priorityExists) {
            return redirect()->back()->with('error', __('tasks.Priority already used'));
        }

        TaskApplication::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'status' => 'pending',
            'priority' => $validated['priority'],
        ]);

        return redirect()->back()->with('success', __('tasks.Successfully applied to task'));
    }

    /**
     * Student cancels application to a task.
     */
    public function cancelApplication(Task $task)
    {
        $application = $task->getApplicationFor(auth()->id());

        if (!$application) {
            return redirect()->back()->with('error', __('tasks.Not applied to this task'));
        }

        $application->delete();

        return redirect()->back()->with('success', __('tasks.Application cancelled'));
    }

    /**
     * Display applications for teacher's tasks.
     */
    public function myApplications()
    {
        // Samo nastavnik može vidjeti prijave
        if (!auth()->user()->isNastavnik()) {
            abort(403, 'Samo nastavnici mogu vidjeti prijave.');
        }

        // Dohvati sve radove nastavnika s prijavama studenata
        $tasks = Task::where('user_id', auth()->id())
            ->with(['applications' => function($query) {
                $query->with('user')->orderBy('priority', 'asc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tasks.applications', compact('tasks'));
    }

    /**
     * Accept a student application (only priority 1 allowed).
     */
    public function acceptApplication(TaskApplication $application)
    {
        // Samo nastavnik koji je vlasnik rada može prihvatiti
        if ($application->task->user_id !== auth()->id()) {
            abort(403, 'Možete prihvatiti samo prijave na svoje radove.');
        }

        // Provjera da li je prioritet 1
        if ($application->priority !== 1) {
            return redirect()->back()->with('error', __('tasks.Can only accept priority 1'));
        }

        // Prihvati prijavu
        $application->update(['status' => 'approved']);

        // Odbij ostale prijave za isti rad
        TaskApplication::where('task_id', $application->task_id)
            ->where('id', '!=', $application->id)
            ->update(['status' => 'rejected']);

        return redirect()->back()->with('success', __('tasks.Application accepted'));
    }
}
