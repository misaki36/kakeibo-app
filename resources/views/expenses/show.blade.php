<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            支出の詳細
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-gray-500">日付</dt>
                        <dd class="text-lg">{{ $expense->date }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">カテゴリ</dt>
                        {{-- カテゴリが未設定(null)の場合は「未分類」と表示 --}}
                        <dd class="text-lg">{{ $expense->category->name ?? '未分類' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">金額</dt>
                        <dd class="text-lg">¥{{ number_format($expense->amount) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">メモ</dt>
                        {{-- nl2br()で改行を<br>タグに変換して表示。e()でHTMLエスケープしXSS対策も忘れずに --}}
                        <dd class="text-lg whitespace-pre-wrap">{{ $expense->memo ?? '（メモなし）' }}</dd>
                    </div>
                </dl>

                <div class="mt-6">
                    <a href="{{ route('expenses.index') }}" class="text-indigo-600">← 一覧に戻る</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>