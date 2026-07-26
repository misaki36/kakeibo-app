<x-app-layout>
    {{-- ページ上部のヘッダー部分 --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            支出一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- 登録成功時のメッセージ表示 --}}
            {{-- session('success')は、コントローラーのredirect()->with('success', ...)で渡された値 --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- 新規登録ページへのリンク --}}
            <div class="mb-4">
                <a href="{{ route('expenses.create') }}"
                   class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    + 支出を登録
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($expenses->isEmpty())
                    {{-- データが1件もない場合の表示 --}}
                    <p class="text-gray-500">まだ支出が登録されていません。</p>
                @else
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2">日付</th>
                                <th class="py-2">カテゴリ</th>
                                <th class="py-2">金額</th>
                                <th class="py-2">メモ</th>
                                <th class="py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- コントローラーで取得した$expensesを1件ずつ繰り返し表示 --}}
                            @foreach ($expenses as $expense)
                                <tr class="border-b">
                                    <td class="py-2">{{ $expense->date }}</td>
                                    {{-- カテゴリが未設定(null)の場合は「未分類」と表示 --}}
                                    <td class="py-2">{{ $expense->category->name ?? '未分類' }}</td>
                                    {{-- number_format()で金額に3桁区切りのカンマをつける --}}
                                    <td class="py-2">¥{{ number_format($expense->amount) }}</td>
                                    {{-- Str::limit()でメモが長い場合に省略表示 --}}
                                    <td class="py-2">{{ Str::limit($expense->memo, 20) }}</td>
                                    <td class="py-2">
                                        <a href="{{ route('expenses.show', $expense) }}" class="text-indigo-600">詳細</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- ページネーションのリンクを表示 --}}
                    <div class="mt-4">
                        {{ $expenses->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>