<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;

class SubscriptionPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = SubscriptionPackage::all();
        return view('admin.subscriptions.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.subscriptions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'student_limit' => 'required|integer|min:0',
            'staff_limit' => 'required|integer|min:0',
            'user_limit' => 'required|integer|min:0',
            'entry_limit' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $data = $request->all();
        $data['has_tech_support'] = $request->has('has_tech_support');

        SubscriptionPackage::create($data);

        return redirect()->route('subscription-packages.index')->with('success', 'Package created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubscriptionPackage $subscriptionPackage)
    {
        return view('admin.subscriptions.edit', compact('subscriptionPackage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubscriptionPackage $subscriptionPackage)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'student_limit' => 'required|integer|min:0',
            'staff_limit' => 'required|integer|min:0',
            'entry_limit' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $data = $request->all();
        $data['has_tech_support'] = $request->has('has_tech_support');

        $subscriptionPackage->update($data);

        return redirect()->route('subscription-packages.index')->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscriptionPackage $subscriptionPackage)
    {
        if ($subscriptionPackage->schools()->count() > 0) {
            return redirect()->route('subscription-packages.index')->with('error', 'Cannot delete package with active schools.');
        }

        $subscriptionPackage->delete();

        return redirect()->route('subscription-packages.index')->with('success', 'Package deleted successfully.');
    }
}
