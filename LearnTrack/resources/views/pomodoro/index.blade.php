<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ポモドーロタイマー</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --bg-green: #b3cfad;
            --bg-light-gray: #e3e6d8;
            --text-brown: #9f9579;
            --accent-yellow: #d9ca79;
        }
        body {
            font-family: 'Arial', sans-serif;
        }
        .sidebar {
            transition: all 0.3s ease;
        }
        .sidebar.hidden {
            transform: translateX(-100%);
        }
        .sidebar-content {
            padding: 20px;
        }
    </style>
</head>
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] flex flex-col lg:flex-row">

    <div id="sidebar" class="fixed inset-y-0 left-0 w-72 shadow-md bg-white z-20 hidden lg:block">
        @include('components.sidebar')
    </div>

    <div id="mainContent" class="flex-1 p-4 sm:p-6 mt-4 lg:ml-72 transition-all">
        <header class="flex sm:flex-row justify-between items-center space-y-4 sm:space-y-0 mb-8">
            <div class="flex items-center justify-between w-full sm:w-auto">
                <h1 class="text-2xl font-semibold text-[var(--text-brown)]">ポモドーロタイマー</h1>
                <button id="menuButton"
                        class="fixed top-6 right-6 bg-[var(--accent-yellow)] text-white p-3 rounded-lg shadow-lg hover:bg-yellow-500 transition-transform transform hover:scale-110 lg:hidden z-[9999]">
                    <img id="menuIcon" src="{{ asset('images/menu_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                </button>
            </div>
        </header>

        <div class="mb-6">
            <label for="planSelect" class="block text-lg font-medium">学習プラン</label>
            <select id="planSelect" class="w-full p-2 mt-2 border border-gray-300 rounded-lg focus:ring-[var(--accent-yellow)] focus:border-[var(--accent-yellow)]">
                <option value="" selected>選択しない</option>
                @foreach ($plans as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-6">
            <label for="studyTime" class="block text-lg font-medium mb-2">学習時間 (分)</label>
            <input type="number" id="studyTime" name="study_time" min="1" max="180"
                   class="w-full p-2 border border-gray-300 rounded-lg focus:ring-[var(--accent-yellow)] focus:border-[var(--accent-yellow)]"
                   placeholder="例: 25">
        </div>
        <div class="mb-6">
            <label for="breakTime" class="block text-lg font-medium mb-2">休憩時間 (分)</label>
            <input type="number" id="breakTime" name="break_time" min="1" max="60"
                   class="w-full p-2 border border-gray-300 rounded-lg focus:ring-[var(--accent-yellow)] focus:border-[var(--accent-yellow)]"
                   placeholder="例: 5">
        </div>
        <div class="flex justify-center items-center mb-6">
            <span id="timer" class="text-5xl font-semibold text-[var(--accent-yellow)]">00:00</span>
        </div>

        <div class="flex space-x-4 justify-center">
            <button id="start-button" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all transform hover:scale-105">タイマー開始</button>
            <button id="restart-button" style="display: none;" class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all transform hover:scale-105">再度開始</button>
            <button id="stop-button" style="display: none;" class="px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all transform hover:scale-105">タイマー停止</button>
            <button id="reset-button" style="display: none;" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all transform hover:scale-105">リセット</button>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            let timerInterval = null;
            let totalSeconds = 0;
            let isBreakTime = false;
            let studySessionId = null;
            let totalStudyTime = 0;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function updateTimerDisplay(seconds) {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                $("#timer").text(
                    `${String(minutes).padStart(2, "0")}:${String(remainingSeconds).padStart(2, "0")}`
                );
            }

            function startTimer(duration, onComplete) {
                totalSeconds = duration;
                updateTimerDisplay(totalSeconds);

                timerInterval = setInterval(() => {
                    totalSeconds--;
                    updateTimerDisplay(totalSeconds);

                    if (totalSeconds <= 0) {
                        clearInterval(timerInterval);
                        timerInterval = null;
                        onComplete();
                    }
                }, 500);
            }

            function startStudyTimer() {
                const studyTime = parseInt($("#studyTime").val()) * 60;
                isBreakTime = true;
                console.log("勉強タイマー開始");
                startTimer(studyTime, () => {
                    console.log("勉強タイマー終了");
                    totalStudyTime += studyTime;
                    startBreakTimer();
                });
            }

            function startBreakTimer() {
                const breakTime = parseInt($("#breakTime").val()) * 60;
                isBreakTime = false;
                console.log("休憩タイマー開始");
                startTimer(breakTime, () => {
                    console.log("休憩タイマー終了");
                    startStudyTimer();
                });
            }

            function getCurrentTime() {
                return new Date().toLocaleString('ja-JP', { timeZone: 'Asia/Tokyo' });
            }

            $("#start-button").click(() => {
                if (!timerInterval) {
                    const japanTime = getCurrentTime();

                    startStudyTimer();
                    $.ajax({
                        url: '/timer/pomodoro/start/' + $('#planSelect').val(),
                        type: 'POST',
                        data: { start_time: japanTime },
                        success: function (response) {
                            if (response.studySessionId) {
                                studySessionId = response.studySessionId;
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('エラーが発生しました:', error);
                            clearInterval(timerInterval);
                            timerInterval = null;
                        }
                    });
                } else {
                    console.warn("タイマーは既に動作中です");
                    return;
                }
                $("#start-button").hide();
                $("#stop-button").show();
                $("#reset-button").show();
            });

            $("#restart-button").click(() => {
                if (!timerInterval && studySessionId) {
                    const time = parseInt($("#studyTime").val()) * 60;
                    console.log("タイマーを再開します");
                    if (isBreakTime) {
                        totalStudyTime -= time - totalSeconds;
                    }
                    startTimer(totalSeconds, () => {
                        if (isBreakTime) {
                            console.log("休憩タイマーを開始しました");
                            startBreakTimer();
                        } else {
                            console.log("勉強タイマーを開始しました");
                            startStudyTimer();
                        }
                    });
                }else {
                    console.warn('タイマーは既に動作中か、セッションIDがありません');
                    return;
                }


                $("#restart-button").hide();
                $("#stop-button").show();
            });

            $("#stop-button").click(() => {
                if (timerInterval && studySessionId) {
                    const japanTime = getCurrentTime();
                    const time = parseInt($("#studyTime").val()) * 60;
                    console.log("タイマーを停止しました");
                    clearInterval(timerInterval);
                    timerInterval = null;
                    if (isBreakTime) {
                        totalStudyTime += time - totalSeconds;
                    }
                    $.ajax({
                        url: '/timer/pomodoro/stop/' + studySessionId,
                        type: 'PUT',
                        data: {
                            end_time: japanTime,
                            duration: totalStudyTime
                        },
                        success: function (response) {
                            console.log('タイマー停止: ', response);
                        },
                        error: function (xhr, status, error) {
                            console.error('エラーが発生しました:', error);
                        }
                    });
                }else {
                    console.warn("タイマーは停止中です");
                    return;
                }
                $("#stop-button").hide();
                $("#restart-button").show();
            });

            $("#reset-button").click(() => {
                console.log("タイマーをリセットしました");
                clearInterval(timerInterval);
                timerInterval = null;
                totalSeconds = 0;
                totalStudyTime = 0;
                studySessionId = null;
                isBreakTime = false;

                updateTimerDisplay(totalSeconds);
                $("#restart-button").hide();
                $("#stop-button").hide();
                $("#reset-button").hide();
                $("#start-button").show();
            });
        });

        document.getElementById("menuButton").addEventListener("click", () => {
            document.getElementById("sidebar").classList.toggle("hidden");
        });
    </script>
</body>
</html>
