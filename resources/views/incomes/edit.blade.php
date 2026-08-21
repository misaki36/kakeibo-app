<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            収入を編集
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- action属性: route('incomes.update', $income) で /incomes/{id} というURLを生成 --}}
                <form method="POST" action="{{ route('incomes.update', $income) }}">
                    @csrf
                    {{-- HTMLのformはGET/POSTしか送れないため、PUTで送りたいことをLaravelに伝える隠しフィールド --}}
                    @method('PUT')

                    {{-- 金額 --}}
                    <div class="mb-4">
                        <label for="amount" class="block font-medium text-sm text-gray-700">金額</label>
                        {{-- old('amount', $income->amount) → 初回表示時は既存のデータを初期値にする --}}
                        <input type="number" name="amount" id="amount" value="{{ old('amount', $income->amount) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('amount')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 収入日 --}}
                    <div class="mb-4">
                        <label for="date" class="block font-medium text-sm text-gray-700">日付</label>
                        <input type="date" name="date" id="date" value="{{ old('date', $income->date->format('Y-m-d')) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- メモ --}}
                    <div class="mb-4">
                        <label for="memo" class="block font-medium text-sm text-gray-700">メモ</label>
                        <textarea name="memo" id="memo" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('memo', $income->memo) }}</textarea>
                        @error('memo')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            更新する
                        </button>
                        <a href="{{ route('incomes.show', $income) }}" class="text-gray-600">キャンセル</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>