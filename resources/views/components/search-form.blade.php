{{-- route：検索先のルート名（'expenses.index' または 'incomes.index'） --}}
@props(['route'])

<div class="mb-4">
    <form method="GET" action="{{ route($route) }}" class="flex items-center gap-2">
        <input type="text" name="keyword" value="{{ request('keyword') }}"
               placeholder="メモを検索..."
               class="border-gray-300 rounded-md shadow-sm">
        <button type="submit" class="px-4 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            検索
        </button>
        @if (request('keyword'))
            <a href="{{ route($route, request()->except('keyword')) }}" class="text-gray-600">
                クリア
            </a>
        @endif
    </form>
</div>