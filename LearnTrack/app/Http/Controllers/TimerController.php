<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;
use App\Models\StudySession;
use App\Models\TimerSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;


class TimerController extends Controller
{
    public function index()
    {
        $plans = Plan::where('user_id', Auth::id())
            ->where('completed', false)
            ->get();
        return view('timer.index', compact('plans'));
    }

    public function pomodoroIndex()
    {
        $user = Auth::user();
        $plans = Plan::where('user_id', $user->id)
            ->where('completed', false)
            ->get();
        return view('pomodoro.index', compact('plans'));
    }

    public function start(Plan $plan = null)
    {
        $userId = Auth::id();

        $unfinishedSession = StudySession::where('user_id', $userId)
            ->whereNull('duration')
            ->first();

        if ($unfinishedSession) {
            $unfinishedSession->delete();
        }

        $newSession = StudySession::create([
            'user_id' => $userId,
            'plan_id' => $plan?->id,
        ]);

        return response()->json(['studySessionId' => $newSession->id], 200);
    }

    public function stop(StudySession $studySession)
    {
        $user = Auth::user();

        try{
            DB::transaction(function () use ($user, $studySession) {
                $duration = request()->input('duration');
                $count = request()->input('count');
                $userCount = $user->count + $count;
                $studySession->update([
                    'duration' => $duration,
                ]);

                $user->update([
                    'count' => $userCount
                ]);

                $plan = $studySession->plan;

                if ($plan && $plan->target_hours > 0) {
                    $totalDuration = $plan->studySessions()->sum('duration');
                    $targetSeconds = $plan->target_hours * 3600;//時間を秒数に
                    $progress = min(round(($totalDuration / $targetSeconds) * 100 ,2),100);//round(,1)で小数点1まで,	min(, 100)で最大値100まで指定
                    $plan->update([
                        'progress' => $progress,
                        'completed' => $progress >= 100,
                    ]);
                }
            });
        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['error' => 'タイマーの終了に失敗しました']);
        }

        return response()->json(['message' => 'タイマー停止']);
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
