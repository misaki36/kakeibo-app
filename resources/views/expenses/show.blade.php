<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            支出の詳細
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

               {{-- 更新成功時のメッセージ表示（index.blade.phpと同じ内容） --}}
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-gray-500">日付</dt>
                        {{-- format('Y-m-d')で時刻部分を省いて日付だけ表示する --}}
                        <dd class="text-lg">{{ $expense->date->format('Y-m-d') }}</dd>
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
                    <div>
                        <dt class="text-sm text-gray-500">支払期日</dt>
                        {{-- due_dateが設定されていれば日付を表示、なければ「未設定」と表示 --}}
                        <dd class="text-lg">
                            {{ $expense->due_date ? $expense->due_date->format('Y-m-d') : '未設定' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">重要度</dt>
                        <dd class="text-lg">{{ $expense->priority }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">状態</dt>
                        <dd class="text-lg">
                            {{-- is_paidがtrueなら「支払い済み」、falseなら「未払い」を色分けして表示 --}}
                            @if ($expense->is_paid)
                                <span class="text-green-600">支払い済み</span>
                            @else
                                <span class="text-red-600">未払い</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">レシート画像</dt>
                        <dd class="text-lg">
                            {{-- receipt_imageが設定されていれば画像を表示、なければ「なし」と表示 --}}
                            @if ($expense->receipt_image)
                                {{-- route('expenses.receipt-image', $expense)：
                                     画像を直接公開URLで配信せず、専用ルート経由で取得する。
                                     ExpenseController@receiptImageの中でPolicyによる本人確認が行われる --}}
                                <img src="{{ route('expenses.receipt-image', $expense) }}" alt="レシート画像"
                                     class="max-w-xs rounded border border-gray-200">
                            @else
                                なし
                            @endif
                        </dd>
                    </div>
                </dl>

                <div class="mt-6 flex items-center gap-4">
                    <a href="{{ route('expenses.index') }}" class="text-indigo-600">← 一覧に戻る</a>
                    <a href="{{ route('expenses.edit', $expense) }}" class="text-blue-600">編集する</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>