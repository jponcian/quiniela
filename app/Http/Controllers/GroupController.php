<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $myGroups = $user->groups;
        
        return view('groups.index', compact('myGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'code' => strtoupper(Str::random(6)),
            'user_id' => Auth::id(),
        ]);

        // El creador se une automáticamente
        $group->users()->attach(Auth::id());

        return back()->with('success', "Grupo '{$group->name}' creado. Código: {$group->code}");
    }

    public function join(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:groups,code',
        ]);

        $group = Group::where('code', strtoupper($request->code))->firstOrFail();

        if ($group->users()->where('user_id', Auth::id())->exists()) {
            return back()->with('error', 'Ya eres miembro de este grupo.');
        }

        $group->users()->attach(Auth::id());

        return back()->with('success', "Te has unido al grupo '{$group->name}'.");
    }

    public function leave(Group $group)
    {
        if ($group->user_id === Auth::id()) {
            return back()->with('error', 'El dueño no puede salir del grupo. Debes eliminarlo.');
        }

        $group->users()->detach(Auth::id());

        return back()->with('success', 'Has salido del grupo.');
    }

    public function delete(Group $group)
    {
        if ($group->user_id !== Auth::id()) {
            return back()->with('error', 'Solo el dueño puede eliminar el grupo.');
        }

        $group->delete();

        return back()->with('success', 'Grupo eliminado.');
    }
}
