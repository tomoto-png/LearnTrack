<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\StudySession;
use App\Models\TimerSetting;
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

    public function pomodoroIndex()
    {
        $user = Auth::user();
        $plans = Plan::where('user_id', $user->id)->get();
        $studySession = StudySession::where('user_id', $user->id)->latest('start_time')->first();
        return view('pomodoro.index', compact('plans', 'studySession'));
    }

    public function pomodoroStart(Plan $plan = null)
    {
        $user = Auth::user();
        $unfinishedSession = StudySession::where('user_id', $user->id)
        ->whereNull('end_time')
        ->first();
        if ($unfinishedSession) {
            $unfinishedSession->delete();
        }

        $startTime = Carbon::parse(request()->input('pomodoro_start_time', now()));
        $newSession = StudySession::create([
            'user_id' => $user->id,
            'plan_id' => $plan?->id,
            'start_time' => $startTime,
        ]);

        return response()->json(['studySessionId' => $newSession->id], 200);
    }

    public function pomodoroStop(StudySession $studySession)
    {
        $user = Auth::user();
        $endTime = Carbon::parse(request()->input('end_time', now()));
        $duration = request()->input('duration');
        Log::info('Duration: ' . $duration);

        $studySession->update([
            'end_time' => $endTime,
            'duration' => $duration,
        ]);

        return response()->json(['message' => 'タイマー停止', 'duration' => $duration]);
    }

    public function timerSettings()
    {
        return view('timer.settings');
    }
    public function pomodoroSettings()
    {
        return view('pomodoro.settings');
    }
    public function savePomodoroSettings(Request $request)
    {
        $request->validate([
            'study_time' => 'required|integer|min:1|max:180',
            'break_time' => 'required|integer|min:1|max:60',
            'auto_switch' => 'nullable|boolean',
            'sound_effect' => 'nullable|boolean',
        ], [
            'study_time.required' => '学習時間を入力してください。',
            'study_time.integer' => '学習時間は整数で入力してください。',
            'study_time.min' => '学習時間は1分以上にしてください。',
            'study_time.max' => '学習時間は180分以内にしてください。',

            'break_time.required' => '休憩時間を入力してください。',
            'break_time.integer' => '休憩時間は整数で入力してください。',
            'break_time.min' => '休憩時間は1分以上にしてください。',
            'break_time.max' => '休憩時間は60分以内にしてください。',

            'auto_switch.boolean' => '自動切り替えの値が不正です。',
            'sound_effect.boolean' => '効果音の設定が不正です。',
        ]);
        $user = Auth::user();
        $timerSetting = $user->timerSetting;

        if ($timerSetting) {
            $timerSetting->update([
                'study_time' => $request->input('study_time'),
                'break_time' => $request->input('break_time'),
                'auto_switch' => $request->has('auto_switch'),
                'sound_effect' => $request->has('sound_effect'),
            ]);
        } else {
            TimerSetting::create([
                'user_id' => $user->id,
                'study_time' => $request->input('study_time'),
                'break_time' => $request->input('break_time'),
                'auto_switch' => $request->has('auto_switch'),
                'sound_effect' => $request->has('sound_effect'),
            ]);
        }
        return redirect()->route('pomodoro.index');
    }
    public function saveTimerSettings(Request $request)
    {
        $request->validate([
            'sound_effect' => 'nullable|boolean',
        ], [
            'sound_effect.boolean' => '効果音の設定が不正です。',
        ]);
        $user = Auth::user();
        $timerSetting = $user->timerSetting;

        if ($timerSetting) {
            $timerSetting->update([
                'sound_effect' => $request->has('sound_effect'),
            ]);
        } else {
            TimerSetting::create([
                'user_id' => $user->id,
                'sound_effect' => $request->has('sound_effect'),
            ]);
        }
        return redirect()->route('timer.index');
    }
}
