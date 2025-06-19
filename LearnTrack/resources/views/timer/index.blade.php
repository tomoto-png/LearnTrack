<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>集中タイマー</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --bg-green: #a0b89c;
            --bg-light-gray: #d6d9c8;
            --text-brown: #6b5e3f;
            --button-bg: #6c8c5d;
            --button-hover: #57724a;
            --accent-color: #3f5c38;
            --white: white;
        }
        .numbers {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-[var(--bg-green)] text-[var(--text-brown)]">
    <aside id="sidebar"
        class="fixed top-0 left-0 w-72 h-screen bg-[var(--bg-light-gray)] shadow-lg p-6 z-50
            transform -translate-x-full transition-transform duration-300 ease-in-out
            lg:translate-x-0">
        @include('components.sidebar')
    </aside>

    <div id="mainContent" class="flex-1 p-4 mt-4 sm:p-6 sm:mt-6 lg:ml-72">
        <header class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-[var(--text-brown)]">集中タイマー</h1>
            <!-- 設定ボタン -->
            <a href="{{ route('timer.settings')}}"
                class="flex items-center mr-14 lg:mr-0 transition-transform transform hover:scale-110 hover:rotate-90 duration-300">
                <img src="{{ asset('images/settings_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-7 h-7">
            </a>
            <button id="menuButton"
                class="fixed top-7 right-6 sm:top-10 sm:right-8 bg-[var(--accent-color)] text-[var(--white)] p-2 rounded-lg shadow-lg hover:bg-[var(--button-hover)] transition-transform transform hover:scale-110 lg:hidden z-50">
                <img id="menuIcon" src="{{ asset('images/menu_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
            </button>
        </header>

        <div class="flex items-center mb-6 max-w-2xl mx-auto space-x-10 sm:space-x-40">
            <label for="planSelect" class="block text-lg font-medium">学習プラン</label>
            <select id="planSelect" class="flex-1 w-full p-2 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent-color)]">
                <option value="" selected>選択しない</option>
                @foreach ($plans as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="w-full px-2 md:px-4 py-4 md:py-6 max-w-full md:max-w-4xl mx-auto flex items-center space-x-2 md:space-x-6 overflow-x-auto">
            <div class="flex-1 min-w-0">
                <div class="w-full flex items-center justify-between gap-1 md:gap-2">

                <!-- Seed Icon -->
                    <div class="flex flex-col items-center shrink-0">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex justify-center items-center border-4 border-[var(--white)]">
                        <img src="{{ asset('images/seed.svg') }}" alt="種" class="w-3.5 h-3.5 md:w-4 md:h-4">
                        </div>
                    </div>

                    <div class="flex-1 bg-[var(--white)] rounded-full h-1.5 md:h-2">
                        <div id="progress-line" class="h-1.5 md:h-2 rounded-full"></div>
                    </div>

                    <!-- Sprout Icon -->
                    <div class="flex flex-col items-center shrink-0">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex justify-center items-center border-4 border-[var(--white)]">
                        <img src="{{ asset('images/sprout_ec.png') }}" alt="芽" class="w-5 h-5 md:w-6 md:h-6">
                        </div>
                    </div>

                    <div class="flex-1 bg-[var(--white)] rounded-full h-1.5 md:h-2">
                        <div id="progress-line2" class="h-1.5 md:h-2 rounded-full"></div>
                    </div>

                    <!-- Flower Icon -->
                    <div class="flex flex-col items-center shrink-0">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex justify-center items-center border-4 border-[var(--white)]">
                        <img src="{{ asset('images/—Pngtree—flower hand painted small tomato_7102313.png') }}" alt="花" class="w-5 h-5 md:w-6 md:h-6">
                        </div>
                    </div>

                    <div class="flex-1 bg-[var(--white)] rounded-full h-1.5 md:h-2">
                        <div id="progress-line3" class="h-1.5 md:h-2 rounded-full"></div>
                    </div>

                    <!-- Tomato Icon -->
                    <div class="flex flex-col items-center shrink-0">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex justify-center items-center border-4 border-[var(--white)]">
                        <img src="{{ asset('images/icons8-トマト-48.png') }}" alt="トマト" class="w-5 h-5 md:w-6 md:h-6 opacity-80">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tomato Count -->
            <div class="flex items-center space-x-1 shrink-0">
                <img src="{{ asset('images/icons8-トマト-48.png') }}" alt="トマト" class="w-6 h-6 md:w-7 md:h-7">
                <p class="text-base md:text-lg font-semibold">
                <span>&#x2715;</span>
                <span id="tomatoCountDisplay">0</span>
                </p>
            </div>
        </div>

        <!-- 円形タイマー -->
        <div class="relative w-full aspect-square max-w-[90vw] sm:max-w-[400px] md:max-w-[470px] mb-8 mx-auto flex justify-center items-center">
            <svg class="w-full h-full absolute" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="45" stroke="#e1e1e4" stroke-width="2" fill="none" />
            </svg>
            <svg class="absolute top-0 left-0 w-full h-full rotate-[-90deg]" viewBox="0 0 100 100">
                <circle id="progress-circle" cx="50" cy="50" r="45"
                        stroke="url(#progressGradient)" stroke-width="2" fill="none"
                        stroke-dasharray="282.74" stroke-dashoffset="282.74"
                        stroke-linecap="round" />
                <defs>
                    <linearGradient id="progressGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#ff8c00"/>
                        <stop offset="100%" stop-color="#ff4500"/>
                    </linearGradient>
                </defs>
            </svg>
            <div class="relative flex flex-col justify-center items-center">
                <span id="timer" class="text-5xl sm:text-6xl md:text-7xl numbers font-semibold text-[var(--text-brown)]">00:00</span>
                <p id="timer-status" class="text-base sm:text-lg md:text-xl text-[var(--text-brown)] mt-4">未開始</p>
            </div>
        </div>

        <!-- 操作ボタン -->
        <div class="flex justify-center gap-4 flex-wrap px-2">
            <button id="start-button">
                <img src="{{ asset('images/play_circle_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-12 sm:w-14 md:w-16">
            </button>
            <button id="restart-button" style="display: none;">
                <img src="{{ asset('images/play_circle_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-12 sm:w-14 md:w-16">
            </button>
            <button id="stop-button" style="display: none;">
                <img src="{{ asset('images/pause_circle_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-12 sm:w-14 md:w-16">
            </button>
            <button id="reset-button" style="display: none;">
                <img src="{{ asset('images/replay_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-12 sm:w-14 md:w-16">
            </button>
        </div>
        <audio id="sound1" src="{{ asset('sounds/決定ボタンを押す5.mp3') }}" preload="auto"></audio>
        <audio id="sound2" src="{{ asset('sounds/決定1.mp3') }}" preload="auto"></audio>
    </div>
    @php
        $soundEffect = Auth::user()->timerSetting?->sound_effect ?? true;
    @endphp

    <script>
        $(document).ready(function () {
            let timerInterval;
            let totalSeconds = 0;
            let studySessionId = null;
            let loopSeconds = 0;
            let tomatoCount = 0;
            let count = 0;
            let previousProgress = 0;
            let tomatoCompleted = false;
            let cycleTime = 360;
            let cycleTime2 = 5400;
            let cycleStep2 = 238;
            let cycleStep = 120;
            let soundEffect = @json((bool) $soundEffect);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            function updateTimerDisplay() {
                if (isNaN(totalSeconds) || totalSeconds < 0) {
                    totalSeconds = 0;
                }
                let minutes = Math.floor(totalSeconds / 60);
                let seconds = totalSeconds % 60;
                $("#timer").text(
                    `${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`
                );
            }
            function playSound1() {
                const sound = document.getElementById('sound1');
                sound.currentTime = 0; // 毎回先頭から
                sound.play();
            }

            function playSound2() {
                const sound = document.getElementById('sound2');
                sound.currentTime = 0; // 毎回先頭から
                sound.play();
            }

            function updateProgressCircle() {
                const progressCircle = document.getElementById("progress-circle");
                const circumference = 2 * Math.PI * 45;
                let progress = (totalSeconds % cycleTime2) / cycleTime2;
                if (progress > 1) progress = 1;
                const dashOffset = circumference * (1 - progress);

                progressCircle.setAttribute('stroke-dashoffset', dashOffset);

                if (progress < previousProgress) {
                    if (soundEffect) {
                        playSound2(); // 効果音を再生
                    }
                }

                previousProgress = progress;
            }

            function updateTimerStatus(status) {
                $("#timer-status").text(status);
            }
            $("#start-button").click(function () {
                if (!timerInterval) {
                    updateTimerStatus("学習時間");
                    timerInterval = setInterval(function () {
                        totalSeconds++;
                        loopSeconds++;
                        updateTimerDisplay();
                        updateProgressBar();
                        updateProgressCircle();

                        if (loopSeconds >= cycleTime) {
                            resetIconsAndProgress();
                            loopSeconds = 0;
                            if (soundEffect) {
                                playSound1();
                            }
                        }
                    }, 1000);

                    $.ajax({
                        url: '/timer/start/' + $('#planSelect').val(),
                        type: 'POST',
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
                $("#planSelect").prop("disabled", true);
                $("#start-button").hide();
                $("#stop-button").show();
                $("#reset-button").show();
            });
            function updateProgressBar() {
                const totalTime = cycleStep;
                let progress;

                if (loopSeconds <= cycleStep) {
                    $("#seed-icon-container").css("border-color", "green");
                    progress = Math.floor((loopSeconds / totalTime) * 100);
                    $('#progress-line').css({
                        'width': `${progress}%`,
                        'background-color': 'green'
                    });
                } else if (loopSeconds > cycleStep && loopSeconds <= cycleStep2) {
                    let secondProgress = Math.floor(((loopSeconds - cycleStep) / totalTime) * 100);
                    $("#sprout-icon-container").css("border-color", "green");
                    $('#progress-line2').css({
                        'width': `${secondProgress}%`,
                        'background-color': 'green'
                    });
                    $('#progress-line').css('width', '100%');
                }

                if (loopSeconds > cycleStep2) {
                    let secondProgress2 = Math.floor(((loopSeconds - cycleStep2) / totalTime) * 100);
                    $("#flower-icon-container").css("border-color", "green");
                    $('#progress-line3').css({
                        'width': `${secondProgress2}%`,
                        'background-color': 'green'
                    });
                    $('#progress-line').css('width', '100%');
                    $('#progress-line2').css('width', '100%');
                }

                if (loopSeconds >= cycleTime - 2 && !tomatoCompleted) {
                    $("#tomato-icon-container").css("border-color", "green");
                    tomatoCount++;
                    count++;
                    tomatoCompleted = true;
                    $('#tomatoCountDisplay').text(tomatoCount);
                }
            }
            function resetIconsAndProgress() {
                $('#progress-line').css('width', '0%');
                $('#progress-line2').css('width', '0%');
                $('#progress-line3').css('width', '0%');

                $("#seed-icon-container").css("border-color", "var(--white)");
                $("#sprout-icon div").css("border-color", "var(--white)");
                $("#flower-icon div").css("border-color", "var(--white)");
                $("#tomato-icon div").css("border-color", "var(--white)");

                tomatoCompleted = false;
            }

            $("#restart-button").click(function () {
                if (!timerInterval && studySessionId) {
                    updateTimerStatus("学習時間");
                    timerInterval = setInterval(function () {
                        totalSeconds++;
                        loopSeconds++;
                        updateTimerDisplay();
                        updateProgressBar();
                        updateProgressCircle();

                        if (loopSeconds >= cycleTime) {
                            resetIconsAndProgress();
                            loopSeconds = 0;
                        }
                    }, 1000);
                } else {
                    console.warn('タイマーは既に動作中か、セッションIDがありません');
                }
                $("#restart-button").hide();
                $("#stop-button").show();
            });

            $("#stop-button").click(function () {
                if (timerInterval && studySessionId) {
                    updateTimerStatus("一時停止");
                    clearInterval(timerInterval);
                    timerInterval = null;

                    console.log(count);
                    $.ajax({
                        url: '/timer/stop/' + studySessionId,
                        type: 'PUT',
                        data: {
                            duration: totalSeconds,
                            count: count
                        },
                        success: function (response) {
                            console.log('タイマー停止: ', response);
                        },
                        error: function (xhr, status, error) {
                            console.error('エラーが発生しました:', error);
                        }
                    });
                    count = 0;
                }
                $('#stop-button').hide();
                $('#restart-button').show();
            });

            $("#reset-button").click(function () {
                clearInterval(timerInterval);
                timerInterval = null;
                updateTimerStatus("未開始");

                let startOffset = parseFloat(document.getElementById("progress-circle").getAttribute("stroke-dashoffset"));
                let endOffset = 282.74;
                let step = (startOffset - endOffset) / 20;

                let counter = 0;
                let resetInterval = setInterval(() => {
                    if (counter < 20) {
                        startOffset -= step;
                        document.getElementById("progress-circle").setAttribute("stroke-dashoffset", startOffset);
                        counter++;
                    } else {
                        clearInterval(resetInterval);
                        updateProgressCircle();
                    }
                }, 20);

                studySessionId = null;
                totalSeconds = 0;
                loopSeconds = 0;
                tomatoCount = 0;
                count = 0;
                tomatoCompleted = false;
                previousProgress = 0;
                updateTimerDisplay();
                resetIconsAndProgress();

                $("#planSelect").prop("disabled", false);
                $('#tomatoCountDisplay').text(tomatoCount);
                $("#restart-button").hide();
                $('#stop-button').hide();
                $('#reset-button').hide();
                $("#start-button").show();
            });
        });

        document.getElementById("menuButton").addEventListener("click", () => {
            document.getElementById("sidebar").classList.toggle("-translate-x-full");
        });
    </script>
</body>
</html>
