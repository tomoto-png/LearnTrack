<aside class="flex flex-col w-72 bg-[var(--bg-light-gray)] shadow-lg min-h-screen p-6">
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

            <div class="relative flex items-center space-x-4 group">
                <img src="{{ asset('images/timer_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-7 h-7">
                <p class="text-lg font-medium cursor-pointer group-hover:text-[var(--button-bg)]">タイマー</p>

                <!-- 下に展開するメニュー -->
                <div class="absolute left-0 top-full mt-2 w-48 bg-[var(--white)] shadow-md rounded-md transform scale-y-0 origin-top group-hover:scale-y-100 transition-transform duration-200 ease-out z-10">
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
</aside>
