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
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] flex flex-col lg:flex-row">

    <aside id="sidebar"
        class="fixed top-0 left-0 w-72 h-screen bg-[var(--bg-light-gray)] shadow-lg p-6 z-50
            transform -translate-x-full transition-transform duration-300 ease-in-out
            lg:translate-x-0">
        @include('components.sidebar')
    </aside>

    <div id="mainContent" class="flex-1 p-4 mt-4 sm:p-6 sm:mt-6 lg:ml-72">
        <header class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">
                ポモドーロタイマー
            </h1>
            <a href="{{ route('pomodoro.settings')}}"
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
        <!-- タイマー本体 -->
        <div class="relative w-full aspect-square max-w-[90vw] sm:max-w-[400px] md:max-w-[470px] mb-8 mx-auto flex justify-center items-center">
            <!-- 外円 -->
            <svg class="w-full h-full absolute" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="45" stroke="#e1e1e4" stroke-width="2" fill="none" />
            </svg>

            <!-- プログレス円 -->
            <svg class="absolute top-0 left-0 w-full h-full rotate-[-90deg]" viewBox="0 0 100 100">
                <circle id="progress-circle" cx="50" cy="50" r="45"
                        stroke="url(#progressGradient)" stroke-width="2" fill="none"
                        stroke-dasharray="282.74" stroke-dashoffset="282.74"
                        stroke-linecap="round"/>
                <defs>
                    <linearGradient id="studyGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#ff8c00"/>
                        <stop offset="100%" stop-color="#ff4500"/>
                    </linearGradient>
                    <linearGradient id="breakGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#32CD32"/>
                        <stop offset="100%" stop-color="#008000"/>
                    </linearGradient>
                    <linearGradient id="progressGradient" xlink:href="#studyGradient" />
                </defs>
            </svg>

            <!-- タイマー表示 -->
            <div class="relative flex flex-col justify-center items-center text-center">
                <span id="timer" class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl numbers font-semibold">
                    {{ str_pad(Auth::user()->timerSetting->study_time ?? 25, 2, '0', STR_PAD_LEFT) }}:00
                </span>
                <p class="text-sm sm:text-base font-medium mt-2">
                    <span id="pomodoroCountDisplay">0</span>ポモドーロ
                </p>
                <p id="timer-status" class="text-base sm:text-lg md:text-xl absolute top-[7.5rem]">
                    未開始
                </p>
            </div>
        </div>

        <!-- コントロールボタン -->
        <div class="flex justify-center gap-3 flex-wrap px-4">
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
        <audio id="sound2" src="{{ asset('sounds/完了6.mp3') }}" preload="auto"></audio>
        <audio id="sound3" src="{{ asset('sounds/決定1.mp3') }}" preload="auto"></audio>
    </div>
    @php
        $autoSwitch = Auth::user()->timerSetting?->auto_switch ?? true;
        $soundEffect = Auth::user()->timerSetting->sound_effect ?? true;
    @endphp
    <script>
        $(document).ready(function () {
            let timerInterval = null;
            let totalSeconds = 0;
            let isBreakTime = false;
            let studySessionId = null;
            let totalStudyTime = 0;
            let loopSeconds = 0;
            let pomodoroCount = 0;
            let tomatoCount = 0;
            let count = 0;
            let tomatoCompleted = false;
            let cycleTime = 360;
            let cycleStep2 = 239;
            let cycleStep = 120;
            let studyCycleTime = parseInt("{{ Auth::user()->timerSetting->study_time ?? 25 }}") * 60;
            let breakCycleTime = parseInt("{{ Auth::user()->timerSetting->break_time ?? 5 }}") * 60;
            let autoSwitch = @json((bool) $autoSwitch);
            let soundEffect = @json((bool) $soundEffect);

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
            function updateTimerStatus(status) {
                $("#timer-status").text(status);
            }

            function playSound1() {
                const sound = document.getElementById('sound1');
                sound.currentTime = 0; // 毎回先頭から
                sound.play();
            }

            function playSound2() {
                const sound = document.getElementById('sound2');
                sound.currentTime = 0;
                sound.play();
            }

            function playSound3() {
                const sound = document.getElementById('sound3');
                sound.currentTime = 0;
                sound.play();
            }

            function updateProgressCircle() {
                const progressCircle = document.getElementById("progress-circle");
                const circumference = 2 * Math.PI * 45;

                let totalTime = isBreakTime ? studyCycleTime : breakCycleTime;
                let progress = totalSeconds / totalTime;
                if (progress > 1) progress = 1;

                const dashOffset = circumference * progress;
                progressCircle.setAttribute('stroke-dashoffset', dashOffset);

                if (isBreakTime) {
                    progressCircle.setAttribute("stroke", "url(#studyGradient)");
                } else {
                    progressCircle.setAttribute("stroke", "url(#breakGradient)");
                }
            }

            function startTimer(duration, onComplete) {
                totalSeconds = duration;
                updateProgressCircle();
                updateTimerDisplay(totalSeconds);

                timerInterval = setInterval(() => {
                    totalSeconds--;
                    updateProgressBar();
                    updateProgressCircle();
                    updateTimerDisplay(totalSeconds);

                    if (totalSeconds <= 0) {
                        clearInterval(timerInterval);
                        timerInterval = null;
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
                                onComplete();
                            }
                        }, 20);
                    }
                    if (isBreakTime) {
                        loopSeconds++;
                        if (loopSeconds >= cycleTime) {
                            resetIconsAndProgress();
                            loopSeconds = 0;
                            if (soundEffect) {
                                playSound1();
                            }

                        }
                    }
                }, 1000);
            }

            function startStudyTimer() {
                isBreakTime = true;
                updateTimerStatus("学習時間");
                console.log("勉強タイマー開始");
                startTimer(studyCycleTime, () => {
                    console.log("勉強タイマー終了");
                    totalStudyTime += studyCycleTime;
                    if (soundEffect) {
                        playSound3();
                    }
                    startBreakTimer();
                });
            }
            function startBreakTimer() {
                isBreakTime = false;
                updateTimerStatus("休憩時間");
                console.log("休憩タイマー開始");
                startTimer(breakCycleTime, () => {
                    pomodoroCount++;
                    $("#pomodoroCountDisplay").text(pomodoroCount);
                    startStudyTimer();
                    if (soundEffect){
                        playSound2();
                    }
                    if (!autoSwitch) {
                        stop();
                    }
                });
            }

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
                } else if (loopSeconds > cycleStep) {
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
                    count++;
                    tomatoCount++;
                    tomatoCompleted = true;
                    $("#tomatoCountDisplay").text(tomatoCount);
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
            function stop() {
                if (timerInterval && studySessionId) {
                    updateTimerStatus("一時停止");
                    const time = parseInt("{{ Auth::user()->timerSetting->study_time ?? 25 }}") * 60;
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
                            count: count,
                            duration: totalStudyTime
                        },
                        success: function (response) {
                            console.log('タイマー停止: ', response);
                        },
                        error: function(xhr, error) {
                            if (xhr.status === 419 || xhr.status === 401) {
                                alert('セッションが切れました。再度ログインしてください。');
                                window.location.href = '/login';
                            } else {
                                console.error('送信エラー:', error);
                            }
                        }
                    });
                    count = 0;
                } else {
                    console.warn("タイマーは停止中です");
                    return;
                }
                $("#stop-button").hide();
                $("#restart-button").show();
            }

            $("#start-button").click(() => {
                if (!timerInterval) {
                    startStudyTimer();
                    $.ajax({
                        url: '/timer/pomodoro/start/' + $('#planSelect').val(),
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
                    console.warn("タイマーは既に動作中です");
                    return;
                }
                $("#planSelect").prop("disabled", true);
                $("#start-button").hide();
                $("#stop-button").show();
                $("#reset-button").show();
            });

            $("#stop-button").click(() => {
                stop();
            });

            $("#restart-button").click(() => {
                if (!timerInterval && studySessionId) {
                    const time =  parseInt("{{ Auth::user()->timerSetting->study_time ?? 25 }}") * 60;
                    if (isBreakTime) {
                        totalStudyTime -= time - totalSeconds;
                        updateTimerStatus("学習時間");
                    } else {
                        updateTimerStatus("休憩時間");
                    }
                    startTimer(totalSeconds, () => {
                        if (isBreakTime) {
                            totalStudyTime += studyCycleTime;
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

            $("#reset-button").click(() => {
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

                totalSeconds = parseInt("{{ Auth::user()->timerSetting->study_time ?? 25 }}") * 60;

                totalStudyTime = 0;
                studySessionId = null;
                isBreakTime = false;
                loopSeconds = 0;
                tomatoCount = 0;
                count = 0;
                pomodoroCount = 0;

                resetIconsAndProgress();
                updateTimerDisplay(totalSeconds);

                $("#planSelect").prop("disabled", false);
                $("#tomatoCountDisplay").text(tomatoCount);
                $("#pomodoroCountDisplay").text(pomodoroCount);
                $("#restart-button").hide();
                $("#stop-button").hide();
                $("#reset-button").hide();
                $("#start-button").show();
            });
        });

        document.getElementById("menuButton").addEventListener("click", () => {
            document.getElementById("sidebar").classList.toggle("-translate-x-full");
        });
    </script>
</body>
</html>
