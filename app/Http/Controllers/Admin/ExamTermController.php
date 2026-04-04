<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamTerm;
use Illuminate\Http\Request;

class ExamTermController extends Controller
{
    public function index()
    {
        $examTerms = ExamTerm::latest()->get();
        return view('admin.exam_terms.index', compact('examTerms'));
    }

    public function create()
    {
        return view('admin.exam_terms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'session_year' => 'required|string|max:20',
            'is_active' => 'boolean',
        ]);

        if ($request->is_active) {
            ExamTerm::where('school_id', auth()->user()->school_id)->update(['is_active' => false]);
        }

        ExamTerm::create([
            'school_id' => auth()->user()->school_id,
            'name' => $request->name,
            'session_year' => $request->session_year,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.exam-terms.index')
            ->with('success', 'Exam Term created successfully.');
    }

    public function edit(ExamTerm $examTerm)
    {
        return view('admin.exam_terms.edit', compact('examTerm'));
    }

    public function update(Request $request, ExamTerm $examTerm)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'session_year' => 'required|string|max:20',
            'is_active' => 'boolean',
        ]);

        if ($request->is_active) {
            ExamTerm::where('school_id', auth()->user()->school_id)
                ->where('id', '!=', $examTerm->id)
                ->update(['is_active' => false]);
        }

        $examTerm->update([
            'name' => $request->name,
            'session_year' => $request->session_year,
            'is_active' => $request->boolean('is_active', $examTerm->is_active),
        ]);

        return redirect()->route('admin.exam-terms.index')
            ->with('success', 'Exam Term updated successfully.');
    }

    public function destroy(ExamTerm $examTerm)
    {
        $examTerm->delete();
        return redirect()->route('admin.exam-terms.index')
            ->with('success', 'Exam Term deleted successfully.');
    }
}
