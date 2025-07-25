<div class="ml-4">
    <div class="mb-10 mt-5 flex items-center space-x-2">
        <img src="{{ asset('images/tomototomato.png') }}" class="w-[25px] h-[28px] object-cover">
        <img src="{{ asset('images/logo.png') }}" class="w-[68px] h-[29px] object-contain">
    </div>

    <!-- ナビゲーションメニュー -->
    <nav class="space-y-6">
        <div class="flex items-center space-x-4">
            <img src="{{ asset('images/account_circle_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-7 h-7">
            <a href="{{ route('profile.index') }}" class="text-lg font-medium hover:text-[var(--button-bg)]">マイページ</a>
        </div>

        <div class="flex items-center space-x-4">
            <img src="{{ asset('images/import_contacts_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-7 h-7">
            <a href="{{ route('plan.index') }}" class="text-lg font-medium hover:text-[var(--button-bg)]">学習計画</a>
        </div>

        <div class="flex items-center space-x-4">
            <img src="{{ asset('images/kkrn_icon_faq_21.svg') }}" class="w-7 h-7">
            <a href="{{ route('question.index') }}" class="text-lg font-medium hover:text-[var(--button-bg)]">質問ひろば</a>
        </div>

        <div class="flex items-center space-x-4">
            <img src="{{ asset('images/signal_cellular_alt_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-7 h-7">
            <a href="{{ route('studyData.index') }}" class="text-lg font-medium hover:text-[var(--button-bg)]">学習データ</a>
        </div>

        <div class="relative flex items-center space-x-4">
            <img src="{{ asset('images/timer_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-7 h-7">

            <p id="timerToggle" class="text-lg font-medium cursor-pointer hover:text-[var(--button-bg)]">
                タイマー
            </p>

            <div id="timerDropdown"
                class="absolute left-0 top-full mt-2 w-48 bg-[var(--white)] shadow-md rounded-md z-10 hidden">
                <ul>
                    <li>
                        <a href="{{ route('timer.index') }}" class="block px-4 py-2 text-sm hover:bg-[var(--button-bg)] hover:text-[var(--white)]">
                            集中タイマー
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pomodoro.index') }}" class="block px-4 py-2 text-sm hover:bg-[var(--button-bg)] hover:text-[var(--white)]">
                            ポモドーロタイマー
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggle = document.getElementById("timerToggle");
        const dropdown = document.getElementById("timerDropdown");

        toggle.addEventListener("click", function () {
            dropdown.classList.toggle("hidden");
        });

        document.addEventListener("click", function (e) {
            if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add("hidden");
            }
        });
    });
</script>
