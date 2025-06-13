<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>学習データ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- データラベルのライブラリー --}}
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <!-- FullCalendar CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
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
        .fc-button:focus {
            outline: none !important;
            box-shadow: none !important;
        }
        .fc-button:hover {
            background-color: var(--button-hover) !important;
            border-color: var(--button-hover) !important;
        }

        .fc-button {
            background-color: var(--button-bg) !important;
            border-color: var(--button-hover) !important;
        }

        .fc-daygrid-day.selected-date {
            outline: 2px solid var(--button-hover) !important;
            outline-offset: -2px;
        }
        .fc-daygrid-day:hover {
            outline: 2px solid var(--button-hover) !important;
            outline-offset: -2px;
        }
        .wheel-container {
            display: flex;
            gap: 16px;
        }
    </style>
</head>
<body class="bg-[var(--bg-green)] text-[var(--text-brown)]">
    <div id="sidebar" class="fixed inset-y-0 left-0 z-50 hidden lg:block">
        @include('components.sidebar')
    </div>

    <div id="mainContent" class="flex-1 p-4 mt-4 sm:p-6 sm:mt-6 lg:ml-72">
        <header class="flex justify-between items-center mb-6">
            <h1 class="text-xl sm:text-2xl font-semibold">学習データ</h1>
            <button type="button" id="calendarToggleBtn"
                class="flex items-center mr-14 lg:mr-0 transition-transform transform hover:scale-110  duration-300 z-50">
                <img src="{{ asset('images/calendar_month_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-7 h-7">
            </button>
            <button id="menuButton"
                class="fixed top-7 right-6 sm:top-10 sm:right-8 bg-[var(--accent-color)] text-[var(--white)] p-2 rounded-lg shadow-lg hover:bg-[var(--button-hover)] transition-transform transform hover:scale-110 lg:hidden z-50">
                <img id="menuIcon" src="{{ asset('images/menu_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
            </button>
        </header>
        <div class="flex flex-col w-full items-center space-y-6 px-4">
            {{-- 日付切り替え --}}
            <div class="grid grid-cols-3 items-center justify-center max-w-xl w-full">
                <div class="flex justify-start">
                    <button id="prevDateButton">
                        <img src="{{ asset('images/arrow_back_ios_new_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="">
                    </button>
                </div>
                <div class="text-center">
                    <h1 id="selectedDateLabel" class="text-xl font-bold whitespace-nowrap">今日</h1>
                </div>
                <div class="flex justify-end">
                    <button id="nextDateButton">
                        <img src="{{ asset('images/arrow_forward_ios_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="">
                    </button>
                </div>
            </div>

            {{-- グラフタイプ & 形式切り替え --}}
            <div class="flex flex-col sm:flex-row gap-4 items-center justify-center">
                {{-- グラフタイプ（日/月/年） --}}
                <div id="graph-type-switch" class="relative inline-flex bg-gray-100 rounded-full p-1 shadow-inner">
                    <span id="slider" class="absolute left-0 top-0 w-1/3 h-full bg-[var(--button-bg)] rounded-full shadow transition-all duration-300"></span>
                    <button data-type="day" class="tab relative z-10 w-20 text-center py-2 text-sm font-medium text-[var(--white)]">日</button>
                    <button data-type="month" class="tab relative z-10 w-20 text-center py-2 text-sm font-medium text-gray-600">月</button>
                    <button data-type="year" class="tab relative z-10 w-20 text-center py-2 text-sm font-medium text-gray-600">年</button>
                </div>

                {{-- グラフ形式（円/棒） --}}
                <div id="chart-type" class="flex flex-wrap justify-center gap-3">
                    <button data-type="pie" class="tab px-6 py-2 rounded-lg bg-[var(--button-bg)] text-[var(--white)] font-semibold shadow hover:bg-[var(--button-hover)] whitespace-nowrap">円グラフ</button>
                    <button data-type="bar" class="tab px-6 py-2 rounded-lg bg-[var(--white)] font-semibold shadow hover:bg-gray-100 whitespace-nowrap">棒グラフ</button>
                </div>
            </div>

            {{-- グラフ表示 --}}
            <div class="flex flex-col justify-center items-center w-full max-w-[700px] mx-auto px-2">
                <div class="min-w-[500px] min-h-[500px] sm:w-full sm:h-full">
                    <canvas id="studyPieChart" class="w-full h-full"></canvas>
                </div>
                <p id="chartMessage" class="text-center text-lg sm:text-xl mt-2"></p>
            </div>
        </div>

        <div id="calendarModal" class="fixed inset-0 bg-black bg-opacity-50 justify-center items-center z-50 hidden px-4">
            <div id="calendarContainer" class="bg-[var(--white)] p-4 rounded-lg shadow-lg w-full max-w-3xl space-y-3 overflow-y-auto">
                <div id="calendar"></div>
                <div class="flex space-x-1">
                    <span id="selectedDate" class="text-lg">----</span>
                    <button><img id="option" src="{{ asset('images/stat_minus_1_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg') }}"alt="" class="h-7 w-7" style="filter: brightness(85%)"></button>
                    <button><img id="closeIcon" src="{{ asset('images/stat_1_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="" class="h-7 w-7 hidden" style="filter: brightness(85%)"></button>
                    <button id="changeCalendarBtn" class="hidden">移動</button>
                </div>
                <div class="relative">
                    <div class="absolute top-1/3 left-0 right-3/4 h-1/3 rounded-md bg-gray-300 opacity-30 pointer-events-none z-20"></div>
                    <div class="flex gap-1 text-lg">
                        <div class="w-20 h-24 overflow-y-scroll snap-y snap-mandatory text-center scroll-snap-type-y mandatory hidden" style="scrollbar-width: none;" id="yearPicker"></div>
                        <div class="w-11 h-24 overflow-y-scroll snap-y snap-mandatory text-center scroll-snap-type-y mandatory hidden" style="scrollbar-width: none;" id="monthPicker"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let chart;
        let selectedType = 'day';
        let chartType = 'pie';
        let labelText = '';

        function createChart(labels, data) {
            const ctx = document.getElementById('studyPieChart').getContext('2d');
            const chartCanvas = document.getElementById('studyPieChart');
            const chartMessage = document.getElementById('chartMessage');

            if (chart) {
                chart.destroy(); // 以前のチャートが残っている場合は破棄
            }

            if (labels.length === 0 || data.length === 0) {
                chartCanvas.style.display = 'none'; //グラフのデザインを適用しない
                chartMessage.textContent = '学習データがありません';
                return;
            } else {
                chartCanvas.style.display = 'block'; //グラフのデザインを適用する
                chartMessage.textContent = ''; // メッセージを消す
            }
            //グラフの色を決まり部分
            const predefinedColors = [
                '#4E79A7', // 青（冷静・安定）
                '#F28E2B', // オレンジ（やる気）
                '#E15759', // 赤（注意・集中）
                '#76B7B2', // 緑がかった青（安心）
                '#59A14F', // 緑（成長・自然）
                '#EDC948', // 黄色（明るいけど落ち着きあり）
                '#B07AA1', // 紫（穏やか・中立）
                '#FF9DA7'  // ピンク系（補助的に使用）
            ];

            //背景色に合わせてデータラベルのからを「黒」か「白」に変換
            function getContrastColor(hex) { //hexはグラフのカラーコード
                // 16進数 → 10進数に変換(rgb)
                const r = parseInt(hex.substr(1, 2), 16);//substr(1, 2)によって先頭から1文字目以降を2文字取り出す、parseInt()内に基数を追加数することで十進数に変換する
                const g = parseInt(hex.substr(3, 2), 16);
                const b = parseInt(hex.substr(5, 2), 16);

                // 輝度を計算（YIQ式）光の三原色をもとに赤を29.9%、緑を58.7%、青が11.4%
                const brightness = (r * 299 + g * 587 + b * 114) / 1000;

                // 明るさに応じて黒 or 白
                return brightness > 150 ? '#000000' : '#ffffff';
            }
            //その他は灰色に指定する
            const backgroundColors = labels.map((label, index) => {
                return label === 'その他' ? '#B0BEC5' : predefinedColors[index % predefinedColors.length];//色が足りなくなっても自動で最初にループする処理  lengthは要素数
            });

            const options = {
                maintainAspectRatio: false,
                layout: {
                    padding: 70,
                },
                responsive: true,
                plugins: {
                    legend: { position: 'right' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const totalSeconds = context.raw;
                                const minutes = Math.floor(totalSeconds / 60);
                                const seconds = totalSeconds % 60;

                                const data = context.chart.data.datasets[0].data;//一つ目のデータセットを取得
                                const total = data.reduce((sum, val) => sum + val, 0);//0が初期値、
                                const percentage = ((totalSeconds / total) * 100).toFixed(1);

                                let label = ``;
                                if (minutes > 0) {
                                    label += `${minutes}分`;
                                }
                                if (seconds > 0) {
                                    label += `${seconds}秒`;
                                }
                                label += ` (${percentage}%)`;

                                return label;
                            }
                        }
                    }
                }
            };
            if (chartType === 'bar') {
                options.plugins.datalabels = {
                    display: false //これで明示的にデータラベルを無効化！
                };
                options.scales = {
                    y: {
                        grid: {
                            color: '#eeeeee',//目盛り線の色
                            lineWidth: 1.5,//目盛り線の太さ
                        },
                        ticks: {
                            callback: function(value) {
                                let minutes = Math.floor(value / 60);//秒数から分数の変換
                                return `${minutes}分`;
                            },
                            color: '#333'//ラベル文字色
                        },
                        border: {
                            display: false  // ← 軸の線を非表示にする！
                        }
                    },
                    x: {
                        grid: {
                            color: '#eeeeee',
                            lineWidth: 1.5,
                        },
                        ticks: {
                            color: '#333'
                        },
                        border: {
                            display: false  // ← 軸の線を非表示にする！
                        }
                    }
                };
            } else if (chartType === 'pie') {
                Chart.register(ChartDataLabels);
                options.plugins.datalabels = {
                    //ラベルのカラー
                    color: function(context) { //contextは全体の情報
                        const dataset = context.dataset;
                        const value = dataset.data[context.dataIndex];
                        const total = dataset.data.reduce((sum, v) => sum + v, 0);
                        const percentage = value / total;

                        if (percentage < 0.05) {
                            return '#000000'; // 外側ラベル用に黒
                        }

                        const bgColor = context.dataset.backgroundColor[context.dataIndex];//Chartのデータセットオブジェクトによって背景の背景の色配列を取得、[context.dataIndex]を使用する事で何番目か
                        return getContrastColor(bgColor);
                    },//ラベルのカラー
                    //フォントの設定
                    font: {
                        size: 16
                    },
                    offset: 15,//位置を外側に15pxずらす
                    formatter: (value) => {
                        const minutes = Math.floor(value / 60);
                        const seconds = value % 60;

                        let label = '';
                        if (minutes > 0) {
                            label += `${minutes}分`;
                        }
                        if (seconds > 0) {
                            label += `${seconds}秒`;
                        }

                        return label;
                    },
                    clip: false,
                    anchor: function(context) {
                        return getLabelDisplaySettings(context).anchor;
                    },
                    align: function(context) {
                        return getLabelDisplaySettings(context).align;
                    }
                }
            }

            //データラベルの場所指定
            function getLabelDisplaySettings(context) {
                const value = context.dataset.data[context.dataIndex];
                const total = context.dataset.data.reduce((sum, v) => sum + v, 0);
                const percentage = value / total;//パーセントの計算

                if (percentage < 0.05) {
                    return {
                        anchor: 'end',
                        align: 'end'
                    };
                }
                return {};
            }

            if (selectedType === 'day') {
                labelText = '時間帯ごとの学習時間';
            } else if (selectedType === 'month') {
                labelText = '日ごとの学習時間';
            } else if (selectedType === 'year') {
                labelText = '月ごとの学習時間';
            }

            chart = new Chart(ctx, {
                //グラフの種類の指定
                type: chartType,
                //グラフ内のラベル名やラベル名や値などの設定
                data: {
                    labels: labels,
                    datasets: [{
                        label: labelText,
                        data: data,
                        backgroundColor: backgroundColors,
                        borderWidth: 1.5,
                    }]
                },
                //グラフのデザイン
                options: options
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            createChart(@json($labels), @json($data));//グラフ初期描画
            //カレンダー機能
            const calendarBtn = document.getElementById('calendarToggleBtn');
            const calendarModal = document.getElementById('calendarModal');
            let calendarInitialized = false;
            let selectedDateEl = null;
            const todayStr = new Date().toISOString().split('T')[0];
            let calendar;
            let targetDate = todayStr;

            function closeCalendarModal() {
                $('#calendarModal').removeClass('flex').addClass('hidden');

                if (!yearPicker.classList.contains('hidden')) {
                    yearPicker.classList.add('hidden');
                }
                if (!monthPicker.classList.contains('hidden')) {
                    monthPicker.classList.add('hidden');
                }
                if (!changeCalendarBtn.classList.contains('hidden')) {
                    changeCalendarBtn.classList.toggle('hidden');
                    option.classList.toggle('hidden');
                    closeIcon.classList.toggle('hidden');
                }

                isFirstOpen = true;
            }

            calendarBtn.addEventListener('click', function () {
                if (calendarModal.classList.contains('hidden')) {
                    $('#calendarModal').removeClass('hidden').addClass('flex');
                } else {
                    calendarModal.classList.add('hidden');
                    closeCalendarModal();
                    return;
                }
                // 初期化されてなければカレンダー作成
                if (!calendarInitialized) {
                    const calendarEl = document.getElementById('calendar');
                    calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        locale: 'ja',
                        headerToolbar: {
                            left: 'prev',
                            center: 'title',
                            right: 'next'
                        },
                        events: [],

                        //日付セルのクリックイベント
                        dateClick: function(info) {
                            targetDate = info.dateStr;
                            selectedType = 'day';
                            updateDateLabel(targetDate, selectedType);
                            $.ajax({
                                url: '/studyData/calendar/click',
                                method: 'POST',
                                data: {
                                    date: targetDate,
                                    type: selectedType,
                                    chartType: chartType,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    createChart(response.labels, response.data);
                                    closeCalendarModal();
                                    updateSlideAppearance();
                                },
                                error: function(xhr, status, error) {
                                    if (xhr.status === 419 || xhr.status === 401) {
                                        alert('セッションが切れました。再度ログインしてください。');
                                        window.location.href = '/login';
                                    } else {
                                        console.error('送信エラー:', error);
                                    }
                                }
                            });
                        },

                        //タイトル(年と月)のクリックイベント info内にはFullCalendarのデータがあります コールバック関数の定義
                        datesSet: function(info) {
                            const titleEl = document.querySelector('.fc-toolbar-title');//querySelectorは、該当する最初の1つだけ 取得
                            if (titleEl) {
                                const currentDate = calendar.getDate();//現在表示中のカレンダーの基準日付を取得
                                const year = currentDate.getFullYear();
                                const month = currentDate.getMonth() + 1;
                                const localISO = currentDate.toLocaleString('sv-SE').replace(' ', 'T');//ISO8601 に近いフォーマットに変換

                                //	.innerHTML = ... は、その中身のHTMLを丸ごと書き換える命令 style="cursor:pointer;" によって、マウスカーソルを手の形
                                titleEl.innerHTML = `
                                <span id="fc-title-year" style="cursor:pointer;">${year}年</span>
                                <span id="fc-title-month" style="cursor:pointer; margin-left: 4px;">${month}月</span>
                                `;

                                // 年クリックイベント
                                document.getElementById('fc-title-year').addEventListener('click', () => {
                                    selectedType = 'year';
                                    fetchDateViewData();
                                });

                                // 月クリックイベント
                                document.getElementById('fc-title-month').addEventListener('click', () => {
                                    selectedType = 'month';
                                    fetchDateViewData();
                                });

                                //年と月の非同期処理
                                function fetchDateViewData()  {
                                    targetDate = localISO;
                                    updateDateLabel(targetDate, selectedType);
                                    $.ajax({
                                        url: '/studyData/calendar/click',
                                        method: 'POST',
                                        data: {
                                            date: targetDate,
                                            type: selectedType,
                                            chartType: chartType,
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function(response) {
                                            createChart(response.labels, response.data);
                                            closeCalendarModal();
                                            updateSlideAppearance();
                                        },
                                        error: function(xhr, status, error) {
                                            if (xhr.status === 419 || xhr.status === 401) {
                                                alert('セッションが切れました。再度ログインしてください。');
                                                window.location.href = '/login';
                                            } else {
                                                console.error('送信エラー:', error);
                                            }
                                        }
                                    });
                                }
                            }
                        },
                    });

                    calendar.render();
                    calendarInitialized = true;
                }
                //前回選択した日付のカレンダーが表示する(選択がない時は今日の日付)
                calendar.gotoDate(targetDate);
                updateDisplayedMonth(targetDate);

                setTimeout(() => {
                    const calendarEl = document.getElementById('calendar');//htmlカレンダー本体の要素を取得
                    const dayEls = calendarEl.querySelectorAll('.fc-daygrid-day[data-date]');//data-date 属性を持った日付をすべて取得
                    //各日付の data-dateが選択した日付と一致するか確認
                    dayEls.forEach(dayEl => {
                        //すでに選択されているセルがあれば、その枠線クラスを外す
                        if (dayEl.dataset.date === targetDate) {
                            if (selectedDateEl) {
                                selectedDateEl.classList.remove('selected-date');
                            }
                            //今回選ばれたセルに枠線クラス（例: .selected-date）を追加する
                            selectedDateEl = dayEl;
                            selectedDateEl.classList.add('selected-date');
                        }
                    });
                }, 0);
            });
            function updateSlideAppearance() {
                const tabs = document.querySelectorAll('#graph-type-switch .tab');
                let activeTab = null;

                //タグ一覧から白文字があるものを選択
                tabs.forEach(tab => {
                    if (tab.classList.contains('text-[var(--white)]')) {
                        activeTab = tab;
                    }
                });
                const currentType = activeTab?.getAttribute('data-type');

                if (currentType !== selectedType) {
                    const tabs = document.querySelectorAll('#graph-type-switch .tab');
                    tabs.forEach(tab => {
                        tab.classList.remove('text-[var(--white)]');
                        tab.classList.add('text-gray-600');
                    });

                    const targetTab = document.querySelector(`#graph-type-switch .tab[data-type="${selectedType}"]`);
                    targetTab.classList.remove('text-gray-600');
                    targetTab.classList.add('text-[var(--white)]');

                    const slider = document.getElementById('slider');
                    if (slider) {
                        if (selectedType === 'year') {
                            slider.style.transform = 'translateX(200%)';
                        } else if (selectedType === 'month') {
                            slider.style.transform = 'translateX(100%)';
                        } else if (selectedType === 'day') {
                            slider.style.transform = 'translateX(0%)'
                        }
                    }
                }
            }
            calendarModal.addEventListener('click', function (e) {
                if (!calendarContainer.contains(e.target)) {
                    calendarModal.classList.add('hidden');
                    closeCalendarModal();
                }
            });

            function updateDateLabel(dateStr, type) {
                const dateLabel = document.getElementById('selectedDateLabel');
                const date = new Date(dateStr);

                if (type === 'year') {
                    dateLabel.textContent = `${date.getFullYear()}年`;
                } else if (type === 'month') {
                    dateLabel.textContent = `${date.getFullYear()}年${date.getMonth() + 1}月`;
                } else {
                    if (dateStr === todayStr) {
                        dateLabel.textContent = '今日';
                        return;
                    }
                    const formattedDate = date.toLocaleDateString('ja-JP', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    dateLabel.textContent = formattedDate;
                }
            }


            //年月ホイールピッカー
            const $yearPicker = $('#yearPicker');
            const $monthPicker = $('#monthPicker');
            const loginYear = {{ $loginYear }};//laravelからログインの年
            const now = new Date();//現在時刻を取得
            const currentYear = now.getFullYear();//現在の年を取得

            //ログインから現在の年の動的追加
            $yearPicker.append('<div class="h-8 snap-start"></div>');//上部の空白欄追加
            for (let y = loginYear; y <= currentYear; y++) {
                $yearPicker.append(`<div class="h-8 snap-start">${y}</div>`);
            }
            $yearPicker.append('<div class="h-8"></div>');//下部の空白欄追加

            //12ヶ月分の動的追加
            $monthPicker.append('<div class="h-8 snap-start"></div>');
            for (let m = 1; m <= 12; m++) {
                $monthPicker.append(`<div class="h-8 snap-start">${m}</div>`);
            }
            $monthPicker.append('<div class="h-8"></div>');

            // 中央のアイテムを取得する関数
            function getCenteredItem($picker) {
                const scrollTop = $picker.scrollTop();//現在のスクロール量（上に動かした分がどれくらいか）を取得
                const itemHeight = 32;//ピッカー内のアイテム高さ
                const centerOffset = $picker.height() / 2;//ピッカーの中央位置の計算
                const index = Math.floor((scrollTop + centerOffset) / itemHeight);//中央に見えているアイテムのインデックスを計算
                return $picker.children().eq(index).text();//インデックスに該当するアイテムを取得し、そのテキストを返す。
            }

            // 選択された日付、または現在の日付から年と月を表示する
            function updateDisplayedMonth(dateStr) {
                const [splitYear, splitMonth, splitDay] = dateStr.split('-');
                $('#selectedDate').text(`${splitYear}年 ${parseInt(splitMonth)}月`);
            }

            //中央に当たるアイテムの年月を表示する関数
            function updateSelectedDate() {
                const year = getCenteredItem($yearPicker);//getCenteredItemメソットから返されたテキストから年テキストを取る
                const month = getCenteredItem($monthPicker);//getCenteredItemメソットから返されたテキストから月テキストを取る
                $('#selectedDate').text(`${year}年 ${month}月`);//選択されたテキストに置き換えをする
            }

            //ピッカー内のアイテムを中央からの距離に応じて透明度を調節する
            function updateWheelEffect($picker) {
                const scrollTop = $picker.scrollTop();
                const itemHeight = 32;
                const centerOffset = $picker.height() / 2;

                //$picker の中のすべての <div> 子要素（アイテムたち）を1個ずつループします。index は、ループの数を代入（何番目のアイテムか）。
                $picker.children('div').each(function (index) {
                    const itemTop = index * itemHeight;//各アイテムが上から何pxの位置にあるか
                    const distanceFromCenter = (itemTop - scrollTop) - centerOffset + (itemHeight / 2);//アイテムの中心が、ピッカー中央からどれだけ離れているか
                    const distanceRatio = Math.min(Math.abs(distanceFromCenter) / 45, 1);//Math.abs() により、正負どちらでも同じ効果を持つように、また離れた距離が45px以上になると値をマックス１にする
                    //デザインの適用
                    $(this).css({
                        'transform': 'none',
                        'opacity': 1 - distanceRatio,
                    });
                });

            }

            // 年ピッカーのスクロール停止時に選択を更新
            $yearPicker.on('scroll', function () {
                clearTimeout(this._scrollTimer);//スクロールが止まったタイミングだけ処理するように
                updateWheelEffect($(this));//透明度デザインを適用
                this._scrollTimer = setTimeout(updateSelectedDate, 100);//100ms後に updateSelectedDate() を実行
            });

            // 月ピッカーのスクロール停止時に選択を更新
            $monthPicker.on('scroll', function () {
                clearTimeout(this._scrollTimer);
                updateWheelEffect($(this));
                this._scrollTimer = setTimeout(updateSelectedDate, 100);
            });

            //変更ボタンを押して時にピッカー内の値をカレンダーに適用
            const changeCalendarBtn = document.getElementById('changeCalendarBtn');
            //クリックイベントの処理
            changeCalendarBtn.addEventListener('click', function () {
                const year = getCenteredItem($yearPicker);//選択中の年ピッカーを取得する
                const month = getCenteredItem($monthPicker);//選択中の月ピッカーを取得する

                const paddedMonth = month.toString().padStart(2, '0');//月の値が1桁の時は前に0を追加
                const targetDateStr = `${year}-${paddedMonth}-01`;//選択した日付を"YYYY-MM-01"の形式に変更する

                if (calendar) {
                    calendar.gotoDate(targetDateStr);
                    option.classList.toggle('hidden');
                    closeIcon.classList.toggle('hidden');
                    yearPicker.classList.toggle('hidden');
                    monthPicker.classList.toggle('hidden');
                    changeCalendarBtn.classList.toggle('hidden');
                } else {
                    console.warn("カレンダーが初期化されていません");
                }
            });

            const optionSpan = document.getElementById('option');//ピッカーオープンボタンを取得
            let isFirstOpen = true;//初回かどうかの判定
            //クリックイベントの処理
            optionSpan.addEventListener('click', function() {
                option.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
                changeCalendarBtn.classList.toggle('hidden');
                yearPicker.classList.toggle('hidden');
                monthPicker.classList.toggle('hidden');

                //表示されたときだけ下の処理を実行する
                if (!yearPicker.classList.contains('hidden')) {
                    //遅延処理
                    setTimeout(() => {
                        const itemHeight = 32;
                        // 初回のみ現在の年月にスクロール
                        if (isFirstOpen) {
                            const [splitYear, splitMonth, splitDay] = targetDate.split('-');
                            //現在の年が何番目にあるかを計算(0から)
                            const initialYearIndex = splitYear - loginYear;
                            $('#yearPicker').scrollTop(itemHeight * initialYearIndex);

                            //現在の月が何番目にあるかを計算(0から)
                            const initialMonthIndex = splitMonth - 1;
                            $('#monthPicker').scrollTop(itemHeight * initialMonthIndex);

                            isFirstOpen = false; // 初期化済みフラグを更新
                        }
                        updateSelectedDate();//選ばれている年・月に応じて表示を更新する関数
                        //デザインの適応
                        updateWheelEffect($('#yearPicker'));
                        updateWheelEffect($('#monthPicker'));
                    });
                }
            });

            const closeSpan = document.getElementById('closeIcon');
            closeSpan.addEventListener('click', function() {
                option.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
                changeCalendarBtn.classList.toggle('hidden');
                yearPicker.classList.toggle('hidden');
                monthPicker.classList.toggle('hidden');
            });

            //年別や月別、日別の切り替え
            document.querySelectorAll('#graph-type-switch .tab').forEach((btn, index)=> {
                btn.addEventListener('click', function() {
                    selectedType = this.getAttribute('data-type');//thisは現在クリックされた要素.getAttributeはその要素の属性の値を取得
                    updateSlideAppearance();
                    sendChartRequest();
                });
            });
            //グラフのタイプ切り替え
            document.querySelectorAll('#chart-type .tab').forEach(btn =>{
                btn.addEventListener('click',function() {
                    document.querySelectorAll('#chart-type .tab').forEach(b => {
                        b.classList.remove('bg-[var(--button-bg)]', 'text-[var(--white)]', 'hover:bg-[var(--button-hover)]');
                        b.classList.add('bg-[var(--white)]', 'hover:bg-gray-100');
                    });

                    // クリックされたタブに「選択中のクラス」を追加
                    this.classList.remove('bg-[var(--white)]', 'hover:bg-gray-100');
                    this.classList.add('bg-[var(--button-bg)]', 'text-[var(--white)]', 'hover:bg-[var(--button-hover)]');
                    chartType = this.getAttribute('data-type');
                    sendChartRequest();
                })
            });

            function sendChartRequest() {
                const date = targetDate;
                updateDateLabel(date, selectedType);
                $.ajax({
                    url: '/studyData/calendar/click',
                    method: 'POST',
                    data: {
                        date: date,
                        type: selectedType,
                        chartType: chartType,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        createChart(response.labels, response.data);
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 419 || xhr.status === 401) {
                            alert('セッションが切れました。再度ログインしてください。');
                            window.location.href = '/login';
                        } else {
                            console.error('送信エラー:', error);
                        }
                    }
                });
            }

            const prevDate = document.getElementById('prevDateButton');
            const nextDate = document.getElementById('nextDateButton');
            prevDate.addEventListener('click',function() {
                const date = new Date(targetDate);// JavaScript の Date オブジェクト

                if (selectedType === 'day') {
                    date.setDate(date.getDate() - 1);//date.getDate()で何日かを取得、date.setDate()変換した日を適用する
                } else if (selectedType === 'month') {
                    date.setMonth(date.getMonth() - 1);//日付を一か月戻す
                } else if (selectedType === 'year') {
                    date.setFullYear(date.getFullYear() -1);//日付を一年戻す
                }
                updateDate(date);
            });
            nextDate.addEventListener('click',function() {
                const date = new Date(targetDate);

                if (selectedType === 'day') {
                    date.setDate(date.getDate() + 1);
                } else if (selectedType === 'month') {
                    date.setMonth(date.getMonth() + 1);
                } else if (selectedType === 'year') {
                    date.setFullYear(date.getFullYear() + 1);
                };

                updateDate(date);
            });

            function updateDate(date) {
                targetDate = date.toISOString().split('T')[0];//Date オブジェクトを ISO 8601 形式の文字列
                updateDateLabel(targetDate, selectedType);
                $.ajax({
                    url: '/studyData/calendar/click',
                    method: 'POST',
                    data: {
                        date: targetDate,
                        type: selectedType,
                        chartType: chartType,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        createChart(response.labels, response.data);
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 419 || xhr.status === 401) {
                            alert('セッションが切れました。再度ログインしてください。');
                            window.location.href = '/login';
                        } else {
                            console.error('送信エラー:', error);
                        }
                    }
                })
            }
        });

        document.getElementById("menuButton").addEventListener("click", function() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("hidden");
        });
    </script>
</body>
</html>
