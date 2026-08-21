{{-- route：絞り込み先のルート名（'expenses.index' または 'incomes.index'） --}}
@props(['route'])

<div class="mb-4">
    <form method="GET" action="{{ route($route) }}" class="flex items-center gap-2">
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
            <a href="{{ route($route, request()->except(['date_from', 'date_to'])) }}" class="text-gray-600">
                クリア
            </a>
        @endif
    </form>
</div>