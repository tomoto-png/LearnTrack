<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sort = $request->input('sort');

        $query = Plan::query();
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'priority_high':
                $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')");
                break;
            case 'priority_low':
                $query->orderByRaw("FIELD(priority, 'low', 'medium', 'high')");
                break;
        }

        $plans = $query->paginate(6);

        return view('plan.index', compact('plans'));
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
            'target_hours' => 'required|numeric|min:0.5|max:100',
            'priority' => 'required|in:low,medium,high',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
        ]);

        Plan::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'target_hours' => $request->target_hours,
            'priority' => $request->priority,
            'start_date' => $request->start_date,
            'deadline' => $request->deadline,
            'progress' => 0.00,
            'completed' => false,
        ]);

        return redirect()->route('plan.index');
    }

    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        return view('plan.edit', compact('plan'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_hours' => 'required|numeric|min:0.5|max:100',
            'priority' => 'required|in:low,medium,high',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
            'progress' => 'nullable|numeric|min:0|max:100',
            'completed' => 'nullable|boolean',
        ]);

        $plan = Plan::findOrFail($id);
        $plan->update([
            'name' => $request->name,
            'description' => $request->description,
            'target_hours' => $request->target_hours,
            'priority' => $request->priority,
            'start_date' => $request->start_date,
            'deadline' => $request->deadline,
            'progress' => $request->progress ?? $plan->progress,
            'completed' => $request->completed ?? $plan->completed,
        ]);

        return redirect()->route('plan.index');
    }
    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();

        return redirect()->route('plan.index');
    }
}
