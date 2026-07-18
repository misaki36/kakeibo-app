<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            支出を登録
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- action属性: このフォームを送信したときに、どのURLにPOSTするか --}}
                {{-- route('expenses.store')は、routes/web.phpのResource::resource()で自動生成されたURL --}}
                <form method="POST" action="{{ route('expenses.store') }}">
                    {{-- CSRF対策のための隠しトークン。Laravelでは必須のおまじない --}}
                    @csrf

                    {{-- 金額 --}}
                    <div class="mb-4">
                        <label for="amount" class="block font-medium text-sm text-gray-700">金額</label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        {{-- バリデーションエラーがあれば、その内容を表示 --}}
                        @error('amount')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- カテゴリ --}}
                    <div class="mb-4">
                        <label for="category_id" class="block font-medium text-sm text-gray-700">カテゴリ</label>
                        <select name="category_id" id="category_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">未選択</option>
                            {{-- コントローラーで取得した$categoriesを選択肢として展開 --}}
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 支出日 --}}
                    <div class="mb-4">
                        <label for="date" class="block font-medium text-sm text-gray-700">日付</label>
                        <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- メモ --}}
                    <div class="mb-4">
                        <label for="memo" class="block font-medium text-sm text-gray-700">メモ</label>
                        <textarea name="memo" id="memo" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('memo') }}</textarea>
                        @error('memo')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            登録する
                        </button>
                        <a href="{{ route('expenses.index') }}" class="text-gray-600">キャンセル</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>