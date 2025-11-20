<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 4) korisnik vidi projekte gdje je voditelj ili član
    public function index()
    {
        $userId = Auth::id();

        $projects = Project::with(['leader', 'members'])
            ->where('leader_id', $userId)
            ->orWhereHas('members', fn ($q) => $q->where('users.id', $userId))
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $users = User::all();
        return view('projects.create', compact('users'));
    }

    // voditelj je uvijek korisnik koji kreira projekt
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'price'           => 'nullable|numeric',
            'completed_tasks' => 'nullable|string',
            'start_date'      => 'nullable|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
            'members'         => 'array',
            'members.*'       => 'exists:users,id',
        ]);

        $project = Project::create([
            'leader_id'       => Auth::id(),
            'name'            => $data['name'],
            'description'     => $data['description'] ?? null,
            'price'           => $data['price'] ?? null,
            'completed_tasks' => $data['completed_tasks'] ?? null,
            'start_date'      => $data['start_date'] ?? null,
            'end_date'        => $data['end_date'] ?? null,
        ]);

        if (!empty($data['members'])) {
            $project->members()->sync($data['members']);
        }

        return redirect()->route('projects.index');
    }

    public function edit(Project $project)
    {
        $this->authorizeViewOrEdit($project);

        $users = User::all();
        $selectedMembers = $project->members()->pluck('users.id')->toArray();

        return view('projects.edit', compact('project', 'users', 'selectedMembers'));
    }

    // 5) voditelj može sve, član samo "obavljeni poslovi"
    public function update(Request $request, Project $project)
    {
        $user = Auth::user();

        $isLeader = $user->id === $project->leader_id;
        $isMember = $project->members->contains($user->id);

        if (! $isLeader && ! $isMember) {
            abort(403);
        }

        $rules = [
            'completed_tasks' => 'nullable|string',
        ];

        if ($isLeader) {
            $rules = array_merge($rules, [
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string',
                'price'       => 'nullable|numeric',
                'start_date'  => 'nullable|date',
                'end_date'    => 'nullable|date|after_or_equal:start_date',
                'members'     => 'array',
                'members.*'   => 'exists:users,id',
            ]);
        }

        $data = $request->validate($rules);

        if ($isLeader) {
            $project->update([
                'name'            => $data['name'],
                'description'     => $data['description'] ?? null,
                'price'           => $data['price'] ?? null,
                'start_date'      => $data['start_date'] ?? null,
                'end_date'        => $data['end_date'] ?? null,
                'completed_tasks' => $data['completed_tasks'] ?? $project->completed_tasks,
            ]);

            if (isset($data['members'])) {
                $project->members()->sync($data['members']);
            }
        } else { // član
            $project->update([
                'completed_tasks' => $data['completed_tasks'] ?? $project->completed_tasks,
            ]);
        }

        return redirect()->route('projects.index');
    }

    public function destroy(Project $project)
    {
        if (Auth::id() !== $project->leader_id) {
            abort(403);
        }

        $project->delete();

        return redirect()->route('projects.index');
    }

    protected function authorizeViewOrEdit(Project $project): void
    {
        $userId = Auth::id();

        if (
            $project->leader_id !== $userId &&
            ! $project->members->contains($userId)
        ) {
            abort(403);
        }
    }
}