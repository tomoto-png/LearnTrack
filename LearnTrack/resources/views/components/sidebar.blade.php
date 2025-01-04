<aside class="w-72 bg-[var(--bg-light-gray)] shadow-lg min-h-screen p-6 flex flex-col">
    <div class="ml-4">
        <div class="mb-10 mt-5 flex items-center space-x-2">
            <img src="{{ asset('images/tomototomato.png') }}" class="w-[25px] h-[28px] object-cover">
            <img src="{{ asset('images/logo.png') }}" class="w-[68px] h-[29px] object-contain">
        </div>

        <!-- ナビゲーションメニュー -->
        <nav class="space-y-6">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/account_circle_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                <a href="{{ route('profile.index') }}" class="text-lg font-medium hover:text-[var(--accent-yellow)]">マイページ</a>
            </div>

            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/import_contacts_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                <a href="{{ route('plan.index') }}" class="text-lg font-medium hover:text-[var(--accent-yellow)]">学習計画</a>
            </div>

            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/signal_cellular_alt_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                <a href="#" class="text-lg font-medium hover:text-[var(--accent-yellow)]">学習データ</a>
            </div>

            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/timer_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                <a href="#" class="text-lg font-medium hover:text-[var(--accent-yellow)]">タイマー</a>
            </div>

            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/send_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                <a href="#" class="text-lg font-medium hover:text-[var(--accent-yellow)]">共有</a>
            </div>
        </nav>
    </div>
</aside>
