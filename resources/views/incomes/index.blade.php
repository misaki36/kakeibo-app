<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            収入一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- 登録成功時のメッセージ表示 --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- 新規登録ページへのリンク --}}
            <div class="mb-4">
                <a href="{{ route('incomes.create') }}"
                   class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    + 収入を登録
                </a>
            </div>

            {{-- キーワード検索フォーム --}}
            <div class="mb-4">
                <form method="GET" action="{{ route('incomes.index') }}" class="flex items-center gap-2">
                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                           placeholder="メモを検索..."
                           class="border-gray-300 rounded-md shadow-sm">
                    <button type="submit" class="px-4 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        検索
                    </button>
                    @if (request('keyword'))
                        <a href="{{ route('incomes.index', request()->except('keyword')) }}" class="text-gray-600">
                            クリア
                        </a>
                    @endif
                </form>
            </div>

            {{-- 期間フィルタ --}}
            <div class="mb-4">
                <form method="GET" action="{{ route('incomes.index') }}" class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">期間:</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="border-gray-300 rounded-md shadow-sm">
                    <span class="text-gray-600">〜</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="border-gray-300 rounded-md shadow-sm">
                    <button type="submit" class="px-4 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        絞り込む
                    </button>
                    @if (request('date_from') || request('date_to'))
                        <a href="{{ route('incomes.index', request()->except(['date_from', 'date_to'])) }}" class="text-gray-600">
                            クリア
                        </a>
                    @endif
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($incomes->isEmpty())
                    <p class="text-gray-500">まだ収入が登録されていません。</p>
                @else
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2">日付</th>
                                <th class="py-2">金額</th>
                                <th class="py-2">メモ</th>
                                <th class="py-2">★</th>
                                <th class="py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- コントローラーで取得した$incomesを1件ずつ繰り返し表示 --}}
                            @foreach ($incomes as $income)
                                <tr class="border-b">
                                    <td class="py-2">{{ $income->date->format('Y-m-d') }}</td>
                                    <td class="py-2">¥{{ number_format($income->amount) }}</td>
                                    <td class="py-2">{{ Str::limit($income->memo, 20) }}</td>
                                    <td class="py-2">
                                        <div x-data="{ favorited: {{ $income->favoritedByUsers->contains(auth()->id()) ? 'true' : 'false' }} }">
                                            <button
                                                @click="
                                                    fetch('{{ route('incomes.favorite.toggle', $income) }}', {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                                            'Accept': 'application/json',
                                                        },
                                                    })
                                                    .then(res => res.json())
                                                    .then(data => { favorited = data.favorited })
                                                "
                                                :class="favorited ? 'text-yellow-500' : 'text-gray-300'"
                                                class="text-xl"
                                            >
                                                ★
                                            </button>
                                        </div>
                                    </td>
                                    <td class="py-2">
                                        <a href="{{ route('incomes.show', $income) }}" class="text-indigo-600">詳細</a>
                                        <a href="{{ route('incomes.edit', $income) }}" class="text-blue-600 mx-2">編集</a>
                                        {{-- 削除は<a>タグではなく<form>を使う必要がある(DELETEメソッドで送るため) --}}
                                        <form action="{{ route('incomes.destroy', $income) }}" method="POST" class="inline"
                                              onsubmit="return confirm('本当に削除しますか？')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600">削除</button>
                                        </form>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $incomes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>