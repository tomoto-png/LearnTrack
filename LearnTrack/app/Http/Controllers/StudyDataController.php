<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\StudySession;
use App\Models\Plan;
use App\Models\user;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudyDataController extends Controller
{
    public function index(Request $request) {
        $loginYear = Auth::user()->created_at->format('Y');

        $now = Carbon::now();
        $startDate = $now->copy()->startOfDay();
        $endDate = $now->copy()->endOfDay();

        $result = $this->getChartData($startDate, $endDate);

        return view('studyData.index', [
            'labels' => $result['labels'],
            'data' => $result['data'],
            'loginYear'=> $loginYear
        ]);
    }

    public function fetchData(Request $request)
    {
        $date = Carbon::parse($request->input('date'));
        $type = $request->input('type');
        $chartType = $request->input('chartType');

        switch ($type) {
            case 'month':
                $startDate = Carbon::parse($date)->startOfMonth();
                $endDate = Carbon::parse($date)->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::parse($date)->startOfYear();
                $endDate = Carbon::parse($date)->endOfYear();
                break;
            case 'day':
            default:
                $startDate = Carbon::parse($date)->startOfDay();
                $endDate = Carbon::parse($date)->endOfDay();
                break;
        }

        $result = $this->getChartData($startDate, $endDate, $type, $chartType);

        return response()->json([
            'labels' => $result['labels'],
            'data' => $result['data']
        ]);
    }

    private function getChartData($startDate, $endDate, $type = 'month', $chartType = 'bar')
    {
        $sessions = StudySession::where('user_id', Auth::id())
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->get();

        $labels = [];
        $data = [];
        if ($sessions->isEmpty()) {
            return compact('labels', 'data');
        }

        if ($chartType === 'pie') {
            $otherLabel = null;
            $otherData = null;

            $sortedPlanDurations = $sessions->groupBy('plan_id')
                ->map(fn($group) => $group->sum('duration'))
                ->sortDesc();//データの順番を大き順に
            foreach ($sortedPlanDurations as $planId => $seconds) {
                if (empty($planId)) {
                    $otherLabel = 'その他';
                    $otherData = $seconds;
                } else {
                    $plan = Plan::find($planId);
                    $labels[] = $plan ? $plan->name : '不明なプラン';
                    $data[] = $seconds;
                }
            }

            if ($otherLabel !== null) {
                $labels[] = $otherLabel;
                $data[] = $otherData;
            }
        } elseif ($chartType === 'bar') {
            if ($type === 'day') {
                $timeRanges = [
                    '0時〜5時' => ['start' => '00:00:00', 'end' => '05:59:59'],
                    '6時〜11時' => ['start' => '06:00:00', 'end' => '11:59:59'],
                    '12時〜17時' => ['start' => '12:00:00', 'end' => '17:59:59'],
                    '18時〜23時' => ['start' => '18:00:00', 'end' => '23:59:59'],
                ];

                $totals = [];

                foreach ($timeRanges as $label => $item) {
                    $totals[$label] = 0;
                }

                foreach ($sessions as $session) {
                    $time = $session->created_at->format('H:i:s');
                    foreach ($timeRanges as $label => $range) {
                        if ($time >= $range['start'] && $time <= $range['end']) {
                            $totals[$label] += $session->duration;
                            break;
                        }
                    }
                }

                foreach ($totals as $label => $seconds) {
                    $labels[] = $label;
                    $data[] = $seconds;
                }
            } elseif ($type === 'month') {
                $allDates = [];
                $current = $startDate->copy();
                //$current が $endDate より小さい or 同じならループします。
                while ($current <= $endDate) {
                    $key = $current->format('Y-m-d');//日付文字列に変換し代入する
                    $allDates[$key] = 0;//日付の学習時間を0にする
                    $current->addDay();//日付に +1 日次の日に進む
                }

                //実際勉強して時間をここで計算
                $dailyDurations = $sessions->groupBy(fn($session) => $session->created_at->format('Y-m-d'))//日ごとに学習履歴をまとめる（グループにする）
                                           ->map(fn($group) => $group->sum('duration'));//グループごとの時間を足す
                //学習して日付に勉強時間を代入
                foreach ($dailyDurations as $date => $seconds) {
                    $allDates[$date] = $seconds;
                }

                //グラフで表示するデータを作成
                foreach ($allDates as $date => $seconds) {//dailyDurations内の日付と時間をdateとsecondsに代入する
                    $labels[] = Carbon::parse($date)->format('n/j'); // Laravel が使う日付ライブラリ「Carbon」を使って、'2025-05-01'から'5/1'に変換
                    $data[] = $seconds;
                }
            } elseif ($type === 'year') {
                $months = [];
                for ($i = 1; $i <= 12; $i++) {
                    $key = sprintf('%04d-%02d', $startDate->year, $i); //%04d：4桁の整数、ゼロ埋め（例: 2025）, %02d：2桁の整数、ゼロ埋め（例: 01, 02, …, 12）
                    $months[$key] = 0;
                }

                $monthlyDurations = $sessions->groupBy(fn($session) => $session->created_at->format('Y-m'))
                                             ->map(fn($group) => $group->sum('duration'));

                foreach ($monthlyDurations as $date => $seconds) {
                    $months[$date] = $seconds;
                }

                foreach ($months as $month => $seconds) {
                    $labels[] = Carbon::parse($month . '-01')->format('n月');
                    $data[] = $seconds;
                }
            }
        }

        return compact('labels', 'data');
    }
}
