<?php

namespace App\Http\Controllers;

use App\Models\Changelog;
use Illuminate\Http\Request;

class ChangelogController extends Controller
{
    /**
     * Display the application's changelog to users.
     */
    public function index()
    {
        $changelogs = Changelog::where('is_published', true)
            ->latest('release_date')
            ->latest()
            ->get();
            
        return view('user.changelogs', compact('changelogs'));
    }
}
