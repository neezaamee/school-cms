<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Changelog;
use Illuminate\Http\Request;

class ChangelogController extends Controller
{
    public function index()
    {
        $changelogs = Changelog::latest()->get();
        return view('admin.changelogs.index', compact('changelogs'));
    }

    public function create()
    {
        return view('admin.changelogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'version' => 'nullable|string|max:20',
            'description' => 'required|string',
            'type' => 'required|in:feature,improvement,bugfix,security',
            'release_date' => 'nullable|date',
            'is_published' => 'boolean',
        ]);

        Changelog::create($request->all());

        return redirect()->route('admin.changelogs.index')->with('success', 'Changelog entry created successfully.');
    }

    public function edit(Changelog $changelog)
    {
        return view('admin.changelogs.edit', compact('changelog'));
    }

    public function update(Request $request, Changelog $changelog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'version' => 'nullable|string|max:20',
            'description' => 'required|string',
            'type' => 'required|in:feature,improvement,bugfix,security',
            'release_date' => 'nullable|date',
            'is_published' => 'boolean',
        ]);

        $changelog->update($request->all());

        return redirect()->route('admin.changelogs.index')->with('success', 'Changelog entry updated successfully.');
    }

    public function destroy(Changelog $changelog)
    {
        $changelog->delete();
        return redirect()->route('admin.changelogs.index')->with('success', 'Changelog entry deleted.');
    }
}
