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

        $query = Plan::where('user_id', Auth::id());
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        switch ($request->input('sort')) {
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

        $plans = $query->paginate(6)->withQueryString();;

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
            'priority' => 'required|in:low,medium,high'
        ]);

        Plan::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'target_hours' => $request->target_hours,
            'priority' => $request->priority,
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
        ]);

        $plan = Plan::findOrFail($id);

        $totalDuration = $plan->studySessions()->sum('duration');
        $targetSeconds = $request->target_hours * 3600;
        $progress = min(round(($totalDuration / $targetSeconds) * 100 ,2),100);//round(,1)で小数点1まで,	min(, 100)で最大値100まで指定

        $plan->update([
            'name' => $request->name,
            'description' => $request->description,
            'target_hours' => $request->target_hours,
            'priority' => $request->priority,
            'progress' => $progress,
            'completed' => $progress >= 100,
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
