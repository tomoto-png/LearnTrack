<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>プロフィール編集</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    </style>
</head>
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] min-h-screen flex items-center justify-center">
    <div class="px-6 max-w-xl lg:max-w-2xl w-full">
        <div class="p-6 lg:p-8 bg-[var(--bg-light-gray)] rounded-xl shadow-md">
            <h1 class="text-xl font-semibold border-b border-[var(--texy-brown)] pb-3">プロフィール編集</h1>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="space-y-3 mt-2 border-b border-[var(--texy-brown)] pb-4">
                    <div class="flex justify-center">
                        <div class="bg-transparent relative w-32 h-32 border border-dashed border-gray-300 rounded-full">
                            <div id="imagePreview" class="absolute inset-0 items-center justify-center hidden z-10">
                                <img id="previewImage" src="" alt="プレビュー画像" class="w-full h-full object-cover rounded-full shadow-md cursor-pointer">
                            </div>

                            <!-- アイコンボタン -->
                            <label for="image" class="flex flex-col items-center justify-center w-full h-full rounded-full bg-[var(--white)] transition cursor-pointer z-20">
                                <img src="{{ asset('images/person_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="画像アップロード" class="w-12 h-12 opacity-70">
                                <span class="mt-2 text-sm text-gray-600">画像を選択</span>
                                <input type="file" name="avatar" id="image" accept="image/*" class="hidden">
                                @if ($user->avatar)
                                    <input type="hidden" name="existing_avatar" value="{{ $user->avatar }}">
                                @endif
                            </label>
                        </div>
                        @error('avatar')
                            <p>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="name" class="block text-base font-semibold mb-2">
                            名前
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--button-bg)]"
                            value="{{ old('name', $user->name ?? '') }}"
                            placeholder="20文字以内で入力してください"
                            maxlength="20"
                            required
                        >
                        @error('name')
                            <p>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-base font-semibold mb-2">
                            性別
                        </label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input
                                    type="radio"
                                    name="gender"
                                    value="male"
                                    class="accent-[var(--accent-color)]"
                                    {{ old('gender', $user->gender) === 'male' ? 'checked' : '' }}
                                >
                                <span class="ml-2">男性</span>
                            </label>

                            <label class="flex items-center">
                                <input
                                    type="radio"
                                    name="gender"
                                    value="female"
                                    class="accent-[var(--accent-color)]"
                                    {{ old('gender', $user->gender) === 'female' ? 'checked' : '' }}
                                >
                                <span class="ml-2">女性</span>
                            </label>

                            <label class="flex items-center">
                                <input type="radio" name="gender" value="other" class="accent-[var(--accent-color)]"
                                    {{ old('gender', $user->gender) === 'other' ? 'checked' : '' }}>
                                <span class="ml-2">その他</span>
                            </label>

                            <label class="flex items-center">
                                <input
                                    type="radio"
                                    name="gender"
                                    value=""
                                    class="accent-[var(--accent-color)]"
                                    {{ empty(old('gender', $user->gender)) ? 'checked' : '' }}
                                >
                                <span class="ml-2">非公開</span>
                            </label>

                            @error('gender')
                                <p>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="age" class="block text-base font-semibold mb-2">
                            年齢
                        </label>
                        <select id="age" name="age" class="p-2 rounded-md">
                            <option value="" {{ empty($user->age) ? 'selected' : '' }}>非公開</option>
                            <option value="under_10" {{ $user->age === 'under_10' ? 'selected' : '' }}>10歳未満（小学生以下）</option>
                            <option value="10s" {{ $user->age === '10s' ? 'selected' : '' }}>10代（中高生など）</option>
                            <option value="20s" {{ $user->age === '20s' ? 'selected' : '' }}>20代（大学生・社会人など）</option>
                            <option value="30_and_over" {{ $user->age === '30_and_over' ? 'selected' : '' }}>30歳以上</option>
                        </select>
                        @error('age')
                            <p>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="occupation" class="block text-base font-semibold mb-2">
                            職業
                        </label>
                        <input
                            type="text"
                            id="occupation"
                            name="occupation"
                            class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--button-bg)]"
                            maxlength="20"
                            placeholder="20文字以内で入力してください"
                            value="{{ old('occupation', $user->occupation ?? '') }}"
                        >
                    </div>

                    <div>
                        <label for="bio" class="block text-base font-semibold mb-2">
                            自己紹介
                        </label>
                        <textarea
                            id="bio"
                            name="bio"
                            placeholder="200文字以内で入力してください(任意)"
                            class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--button-bg)]"
                            maxlength="200"
                            rows="4">{{ $user->bio ?? ''}}</textarea>
                        @error('bio')
                            <p>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-5">
                    <a href="{{ route('profile.index') }}"
                        class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">キャンセル</a>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-[var(--button-bg)] text-[var(--white)] font-semibold hover:bg-[var(--button-hover)] transition">更新する</button>
                </div>
            </form>
        </div>
        @error('error')
            <script>
                alert("{{ $message }}");
            </script>
        @enderror
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const previewImage = document.getElementById('previewImage');
            const imageInput = document.getElementById('image');
            const imagePreviewWrapper = document.getElementById('imagePreview');

            const existingImage = "{{ $user->avatar ?? '' }}";
            function showImage(src) {
                previewImage.src = src;
                imagePreviewWrapper.classList.remove('hidden');
                imagePreviewWrapper.classList.add('flex');
            }
            if (existingImage) {
                showImage(existingImage)
            }
            // ファイル選択時のプレビュー表示
            imageInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        imagePreviewWrapper.classList.remove('hidden');
                        imagePreviewWrapper.classList.add('flex');
                    };
                    reader.readAsDataURL(file);
                } else {
                    if (existingImage) {
                        showImage(existingImage);
                    } else {
                        imagePreviewWrapper.classList.add('hidden');
                        imagePreviewWrapper.classList.remove('flex');
                        previewImage.src = '';
                    }
                }
            });

            previewImage.addEventListener('click', function() {
                imageInput.click();
            });
        });
    </script>
</body>
</html>
