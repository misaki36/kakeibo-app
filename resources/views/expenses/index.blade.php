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

            {{-- 支払い状況フィルタ --}}
            <div class="mb-4 flex items-center gap-2">
                {{-- 「すべて」リンク：is_paidパラメータなしで一覧を表示（絞り込み解除） --}}
                <a href="{{ route('expenses.index') }}"
                   class="px-3 py-1 rounded {{ request('is_paid') === null ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                    すべて
                </a>
                {{-- 「未払いのみ」リンク：is_paid=0 を付けてアクセス --}}
                <a href="{{ route('expenses.index', ['is_paid' => 0]) }}"
                   class="px-3 py-1 rounded {{ request('is_paid') === '0' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                    未払いのみ
                </a>
                {{-- 「支払い済みのみ」リンク：is_paid=1 を付けてアクセス --}}
                <a href="{{ route('expenses.index', ['is_paid' => 1]) }}"
                   class="px-3 py-1 rounded {{ request('is_paid') === '1' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                    支払い済みのみ
                </a>
            </div>
                {{-- 重要度フィルタ --}}
            <div class="mb-4 flex items-center gap-2">
                <span class="text-sm text-gray-600 mr-2">重要度:</span>
                {{-- 「すべて」：priorityパラメータなしでアクセス --}}
                <a href="{{ route('expenses.index', request()->except('priority')) }}"
                   class="px-3 py-1 rounded {{ request('priority') === null ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                    すべて
                </a>
                {{-- 1〜3をループで生成（コピペミスを防ぐため、for文で3つ分をまとめて作る） --}}
                @for ($i = 1; $i <= 3; $i++)
                    <a href="{{ route('expenses.index', request()->except('priority') + ['priority' => $i]) }}"
                       class="px-3 py-1 rounded {{ request('priority') == $i ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                        {{ $i }}
                    </a>
                @endfor
            </div>

           {{-- カテゴリフィルタ --}}
            <div class="mb-4 flex items-center gap-2">
                <span class="text-sm text-gray-600 mr-2">カテゴリ:</span>
                {{-- 「すべて」：category_idパラメータなしでアクセス --}}
                <a href="{{ route('expenses.index', request()->except('category_id')) }}"
                   class="px-3 py-1 rounded {{ request('category_id') === null ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                    すべて
                </a>
                {{-- $categoriesを1件ずつ繰り返して、カテゴリごとのボタンを生成する --}}
                @foreach ($categories as $category)
                    <a href="{{ route('expenses.index', request()->except('category_id') + ['category_id' => $category->id]) }}"
                       class="px-3 py-1 rounded {{ request('category_id') == $category->id ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <x-search-form route="expenses.index" />
            <x-date-range-filter route="expenses.index" />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($expenses->isEmpty())
                    {{-- データが1件もない場合の表示 --}}
                    <p class="text-gray-500">まだ支出が登録されていません。</p>
                @else
                {{-- overflow-x-auto：横幅が画面を超えたとき、テーブル部分だけ横スクロールできるようにする --}}
                    <div class="overflow-x-auto">
                        {{-- w-fullをやめて、最低幅(min-w)を指定することで、テーブルが画面幅より縮まないようにする --}}
                       <table class="min-w-max text-left">
                
                        <thead>
                            <tr class="border-b">
                                <th class="py-2">日付</th>
                                <th class="py-2">カテゴリ</th>
                                <th class="py-2">金額</th>
                                <th class="py-2">メモ</th>
                                <th class="py-2">支払期日</th>
                                <th class="py-2">重要度</th>
                                <th class="py-2">状態</th>
                                <th class="py-2">画像</th>
                                <th class="py-2">★</th>
                                <th class="py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- コントローラーで取得した$expensesを1件ずつ繰り返し表示 --}}
                            @foreach ($expenses as $expense)
                                <x-expense-row :expense="$expense" />
                            @endforeach
                        </tbody>
                    </table>
                    </div>

                    {{-- ページネーションのリンクを表示 --}}
                    <div class="mt-4">
                        {{ $expenses->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>