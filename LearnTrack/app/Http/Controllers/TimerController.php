<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\TimerSetting;
use App\Models\StudySession;
use Illuminate\Support\Facades\Log;

class TimerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $plans = Plan::where('user_id', $user->id)->get();
        $studySession = StudySession::where('user_id', $user->id)->latest('start_time')->first();
        return view('timer.index', compact('plans', 'studySession'));
    }

    public function start(Plan $plan = null)
    {
        $user = Auth::user();

        $unfinishedSession = StudySession::where('user_id', $user->id)
        ->whereNull('end_time')
        ->first();

        if ($unfinishedSession) {
            $unfinishedSession->delete();
        }

        // 新しいセッションを開始
        $startTime = Carbon::parse(request()->input('start_time', now()));

        $newSession = StudySession::create([
            'user_id' => $user->id,
            'plan_id' => $plan?->id,
            'start_time' => $startTime,
        ]);

        return response()->json(['message' => 'タイマー開始', 'studySessionId' => $newSession->id], 200);
    }

    public function stop(StudySession $studySession)
    {
        $endTime = Carbon::parse(request()->input('end_time', now()));
        $durationInSeconds = request()->input('duration');

        $studySession->update([
            'end_time' => $endTime,
            'duration' => $durationInSeconds,
        ]);

        return response()->json(['message' => 'タイマー停止']);
    }

    public function savePomodoroSettings(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'work_duration' => 'required|integer|min:1|max:180',
            'break_duration' => 'required|integer|min:1|max:60',
            'is_pomodoro' => 'nullable|boolean',
        ]);

        TimerSetting::create([
            'user_id' => $user->id,
            'work_duration' => $request->work_duration,
            'break_duration' => $request->break_duration,
            'is_pomodoro' => $request->has('is_pomodoro'),
        ]);

        return redirect()->route('timer.pomodoro.show');
    }

    public function pomodoroStart(Request $request, Plan $plan = null)
    {
        $user = Auth::user();

        $unfinishedSession = StudySession::where('user_id', $user->id)
            ->whereNull('end_time')
            ->first();
        if ($unfinishedSession) {
            $unfinishedSession->update([
                'end_time' => now(),
                'duration' => now()->diffInMinutes($unfinishedSession->start_time),
            ]);
        }
        $timerSettings = TimerSetting::findOrFail($request->setting_id);

        StudySession::create([
            'user_id' => $user->id,
            'plan_id' => $plan?->id,
            'start_time' => now(),
            'duration' => $timerSettings->work_duration, // ポモドーロ設定に基づく
        ]);

        return redirect()->route('timer.pomodoro.show');
    }

    public function pomodoroStop()
    {
        $user = Auth::user();
        $studySession = StudySession::where('user_id', $user->id)
            ->orderBy('start_time', 'desc')
            ->firstOrFail();

        $studySession->update([
            'end_time' => now(),
            'duration' => now()->diffInMinutes($studySession->start_time),
        ]);

        return redirect()->route('timer.pomodoro.show');
    }

}
