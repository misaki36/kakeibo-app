<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            収入の詳細
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-gray-500">日付</dt>
                        <dd class="text-lg">{{ $income->date }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">金額</dt>
                        <dd class="text-lg">¥{{ number_format($income->amount) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">メモ</dt>
                        <dd class="text-lg whitespace-pre-wrap">{{ $income->memo ?? '（メモなし）' }}</dd>
                    </div>
                </dl>

                <div class="mt-6">
                    <a href="{{ route('incomes.index') }}" class="text-indigo-600">← 一覧に戻る</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>