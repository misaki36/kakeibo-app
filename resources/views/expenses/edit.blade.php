<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            支出を編集
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- action属性: route('expenses.update', $expense) で /expenses/{id} というURLを生成 --}}
                <form method="POST" action="{{ route('expenses.update', $expense) }}" enctype="multipart/form-data">
                    @csrf
                    {{-- HTMLのformはGET/POSTしか送れないため、PUTで送りたいことをLaravelに伝える隠しフィールド --}}
                    @method('PUT')
                    {{-- 金額 --}}
                    <div class="mb-4">
                        <label for="amount" class="block font-medium text-sm text-gray-700">金額</label>
                        {{-- old('amount', $expense->amount) →
                             バリデーションエラーで再表示された場合は入力し直した値を、
                             それ以外(初回表示)の場合は既存のデータ($expense->amount)を初期値にする --}}
                        <input type="number" name="amount" id="amount" value="{{ old('amount', $expense->amount) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
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
                            @foreach ($categories as $category)
                                {{-- old('category_id', $expense->category_id) →
                                     再表示時は入力し直した値、初回表示時は既存のカテゴリを選択状態にする --}}
                                <option value="{{ $category->id }}" @selected(old('category_id', $expense->category_id) == $category->id)>
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
                        {{-- $expense->dateはCarbonインスタンス(Step 9-B-1で$castsを設定したため)なので、
                             format('Y-m-d')でHTMLのdate入力欄が読み取れる形式に変換する --}}
                        <input type="date" name="date" id="date" value="{{ old('date', $expense->date->format('Y-m-d')) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- 支払期日 --}}
                    <div class="mb-4">
                        <label for="due_date" class="block font-medium text-sm text-gray-700">支払期日（任意）</label>
                        {{-- due_dateはnullable(未入力もあり得る)なので、
                             nullの場合にformat()を呼ぶとエラーになる。三項演算子でnullチェックしてから変換する --}}
                        <input type="date" name="due_date" id="due_date"
                               value="{{ old('due_date', $expense->due_date?->format('Y-m-d')) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('due_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- 重要度 --}}
                    <div class="mb-4">
                        <label for="priority" class="block font-medium text-sm text-gray-700">重要度</label>
                        <select name="priority" id="priority"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            {{-- old('priority', $expense->priority) → 初回表示時は既存の重要度を選択状態にする --}}
                            <option value="1" @selected(old('priority', $expense->priority) == 1)>1（低い）</option>
                            <option value="2" @selected(old('priority', $expense->priority) == 2)>2（普通）</option>
                            <option value="3" @selected(old('priority', $expense->priority) == 3)>3（高い）</option>
                        </select>
                        @error('priority')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- 支払い済みフラグ --}}
                    <div class="mb-4">
                        <label class="flex items-center">
                            {{-- old('is_paid', $expense->is_paid) → 初回表示時は既存の支払い状態をチェック状態にする --}}
                            <input type="checkbox" name="is_paid" value="1" @checked(old('is_paid', $expense->is_paid))
                                   class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">支払い済みにする</span>
                        </label>
                        @error('is_paid')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- メモ --}}
                    <div class="mb-4">
                        <label for="memo" class="block font-medium text-sm text-gray-700">メモ</label>
                        {{-- old('memo', $expense->memo) → 初回表示時は既存のメモを表示する --}}
                        <textarea name="memo" id="memo" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('memo', $expense->memo) }}</textarea>
                        @error('memo')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- レシート画像 --}}
                    <div class="mb-4">
                        <label for="receipt_image" class="block font-medium text-sm text-gray-700">レシート画像（任意）</label>

                        {{-- route('expenses.receipt-image', $expense)：
                                     画像を直接公開URLで配信せず、専用ルート経由で取得する。
                                     ExpenseController@receiptImageの中でPolicyによる本人確認が行われる --}}
                                     @if ($expense->receipt_image)
                            <div class="mt-2 mb-2">
                                <img src="{{ route('expenses.receipt-image', $expense) }}" alt="現在のレシート画像"
                                     class="max-w-xs rounded border border-gray-200">
                                <p class="text-sm text-gray-500 mt-1">現在の画像（新しい画像を選ぶと差し替わります）</p>
                            </div>
                        @endif

                        <input type="file" name="receipt_image" id="receipt_image" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-700">
                        @error('receipt_image')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            更新する
                        </button>
                        {{-- キャンセル時は一覧ではなく、詳細画面に戻る方が親切かもしれない --}}
                        <a href="{{ route('expenses.show', $expense) }}" class="text-gray-600">キャンセル</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>