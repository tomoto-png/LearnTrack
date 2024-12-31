<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Auth::user()->plans;
        return view('plan.index', compact('plan'));
    }
    public function create()
    {
        return view('plan.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_hours' => 'required|integer|min:5|max:21',
            'priority' => 'required|in:low,medium,high',
        ]);

        Plan::create([
            'name' => $request-> name,
            'description' => $request-> description,
            'target_hours' => $request-> target_hours,
            'priority' => $request -> priority,
        ]);

        return redirect()->route('plan.index');
    }

    public function edit($id)
    {
        $plan = Plan::find($id);
        return view('plan.edit', compact('plan'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_hours' => 'required|integer|min:5|max:21',
            'priority' => 'required|in:low,medium,high',
        ]);
        $plan = Plan::find($id);
        $plan->update([
            'name' => $request-> name,
            'description' => $request-> description,
            'target_hours' => $request-> target_hours,
            'priority' => $request-> priority,
        ]);

        return redirect()->route('plan.index');
    }
    public function destroy($id)
    {
        $paln = Plan::find($id);
        $plan->delete();

        return redirect()->route("plan.index");
    }
}
