<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>タイマー</title>
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

    <!-- サイドバー -->
    <div id="sidebar" class="fixed inset-y-0 left-0 w-72 shadow-md bg-white z-20 hidden lg:block">
        @include('components.sidebar')
    </div>

    <!-- メインコンテンツ -->
    <div id="mainContent" class="flex-1 p-4 sm:p-6 mt-4 lg:ml-72 transition-all">
        <header class="flex sm:flex-row justify-between items-center space-y-4 sm:space-y-0 mb-8">
            <div class="flex items-center justify-between w-full sm:w-auto">
                <h1 class="text-2xl font-semibold text-[var(--text-brown)]">集中タイマー</h1>
                <button id="menuButton"
                        class="fixed top-6 right-6 bg-[var(--accent-yellow)] text-white p-3 rounded-lg shadow-lg hover:bg-yellow-500 transition-transform transform hover:scale-110 lg:hidden z-[9999]">
                    <img id="menuIcon" src="{{ asset('images/menu_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                </button>
            </div>
        </header>

        <!-- 学習プラン選択 -->
        <div class="mb-6">
            <label for="planSelect" class="block text-lg font-medium">学習プラン</label>
            <select id="planSelect" class="w-full p-2 mt-2 border border-gray-300 rounded-lg focus:ring-[var(--accent-yellow)] focus:border-[var(--accent-yellow)]">
                <option value="" selected>選択しない</option>
                @foreach ($plans as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- タイマー表示 -->
        <div class="flex justify-center items-center mb-6">
            <span id="timer" class="text-5xl font-semibold text-[var(--accent-yellow)]">00:00</span>
        </div>

        <!-- タイマーボタン -->
        <div class="flex space-x-4 justify-center">
            <button id="start-button" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all transform hover:scale-105">タイマー開始</button>
            <button id="restart-button" style="display: none;" class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all transform hover:scale-105">再度開始</button>
            <button id="stop-button" style="display: none;" class="px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all transform hover:scale-105">タイマー停止</button>
            <button id="reset-button" style="display: none;" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all transform hover:scale-105">リセット</button>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            let timerInterval;
            let totalSeconds = 0;
            let studySessionId = null;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function updateTimerDisplay() {
                let minutes = Math.floor(totalSeconds / 60);
                let seconds = totalSeconds % 60;
                $("#timer").text(
                    `${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`
                );
            }

            $("#start-button").click(function () {
                if (!timerInterval) {
                    let now = new Date();
                    let japanTime = now.toLocaleString("ja-JP", { timeZone: "Asia/Tokyo" });
                    timerInterval = setInterval(function () {
                        totalSeconds++;
                        updateTimerDisplay();
                    }, 1000);

                    $.ajax({
                        url: '/timer/start/' + $('#planSelect').val(),
                        type: 'POST',
                        data: { end_time: japanTime },
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
                    console.warn('タイマーは既に動作中です');
                }
                $("#start-button").hide();
                $("#stop-button").show();
                $("#reset-button").show();
            });

            $("#restart-button").click(function () {
                if (!timerInterval && studySessionId) {
                    timerInterval = setInterval(function () {
                        totalSeconds++;
                        updateTimerDisplay();
                    }, 1000);
                } else {
                    console.warn('タイマーは既に動作中か、セッションIDがありません');
                }
                $("#restart-button").hide();
                $("#stop-button").show();
            });

            $("#stop-button").click(function () {
                if (timerInterval && studySessionId) {
                    let now = new Date();
                    let japanTime = now.toLocaleString("ja-JP", { timeZone: "Asia/Tokyo" });
                    clearInterval(timerInterval);
                    timerInterval = null;

                    $.ajax({
                        url: '/timer/stop/' + studySessionId,
                        type: 'PUT',
                        data: {
                            end_time: japanTime,
                            duration: totalSeconds
                        },
                        success: function (response) {
                            console.log('タイマー停止: ', response);
                        },
                        error: function (xhr, status, error) {
                            console.error('エラーが発生しました:', error);
                        }
                    });
                }
                $('#stop-button').hide();
                $('#restart-button').show();
            });

            $("#reset-button").click(function () {
                clearInterval(timerInterval);
                timerInterval = null;
                totalSeconds = 0;
                studySessionId = null;
                updateTimerDisplay();

                $("#restart-button").hide();
                $('#stop-button').hide();
                $('#reset-button').hide();
                $("#start-button").show();
            });
        });

        document.getElementById("menuButton").addEventListener("click", () => {
            document.getElementById("sidebar").classList.toggle("hidden");
        });
    </script>
</body>
</html>
