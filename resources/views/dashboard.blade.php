<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- 今月のサマリーカード（収入・支出・収支の3枚を横並びで表示） --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

                {{-- 今月の収入 --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">今月の収入</p>
                    <p class="text-2xl font-bold text-green-600">¥{{ number_format($thisMonthIncome) }}</p>
                </div>

                {{-- 今月の支出 --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">今月の支出</p>
                    <p class="text-2xl font-bold text-red-600">¥{{ number_format($thisMonthExpense) }}</p>
                </div>

                {{-- 今月の収支（プラスなら緑、マイナスなら赤で表示） --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">今月の収支</p>
                    <p class="text-2xl font-bold {{ $thisMonthBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $thisMonthBalance >= 0 ? '+' : '' }}¥{{ number_format($thisMonthBalance) }}
                    </p>
                </div>
            </div>

            {{-- 先月比較 --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <p class="text-sm text-gray-500 mb-2">先月比較（支出）</p>
                <p class="text-lg">
                    先月の支出：¥{{ number_format($lastMonthExpense) }}
                </p>
                <p class="text-lg mt-1">
                    @if ($expenseDiff > 0)
                        先月より <span class="text-red-600 font-bold">¥{{ number_format($expenseDiff) }}</span> 増えました
                    @elseif ($expenseDiff < 0)
                        先月より <span class="text-green-600 font-bold">¥{{ number_format(abs($expenseDiff)) }}</span> 減りました
                    @else
                        先月と支出額は変わりません
                    @endif
                </p>
            </div>

            {{-- カテゴリ別支出グラフ --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <p class="text-sm text-gray-500 mb-4">今月のカテゴリ別支出</p>

                @if ($categoryBreakdown->isEmpty())
                    <p class="text-gray-500">今月の支出データがまだありません。</p>
                @else
                    {{-- max-w-md で横幅を制限し、aspect-square で縦横比を1:1に固定することで、
                         円グラフが際限なく引き伸ばされるのを防ぐ --}}
                    <div class="max-w-md mx-auto" style="height: 320px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                @endif
            </div>

            {{-- クイックリンク --}}
            <div class="flex gap-4">
                <a href="{{ route('expenses.index') }}" class="text-indigo-600">支出一覧を見る →</a>
                <a href="{{ route('incomes.index') }}" class="text-indigo-600">収入一覧を見る →</a>
            </div>
        </div>
    </div>

    {{-- Chart.js本体をCDN経由で読み込む --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

    {{-- カテゴリごとのデータが1件以上ある時だけ、グラフ描画スクリプトを実行する --}}
    @if ($categoryBreakdown->isNotEmpty())
    <script>
        // Bladeの変数($categoryBreakdown)をJavaScriptで使える形（JSON）に変換する
        // これにより、PHP側で計算したカテゴリごとの金額データを、そのままJavaScriptに渡せる
        const categoryLabels = {!! json_encode($categoryBreakdown->keys()) !!};
        const categoryValues = {!! json_encode($categoryBreakdown->values()) !!};

        // <canvas id="categoryChart">を取得して、その上に円グラフを描画する
        const ctx = document.getElementById('categoryChart');
        new Chart(ctx, {
            type: 'pie', // 円グラフ
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryValues,
                    // カテゴリごとに異なる色を自動で割り当てる
                    backgroundColor: [
                        '#6366f1', '#ef4444', '#22c55e', '#f59e0b',
                        '#06b6d4', '#ec4899', '#8b5cf6', '#84cc16',
                    ],
                }]
            },
            options: {
                responsive: true,
                // maintainAspectRatio: false にすることで、
                // 親要素(<div>)に指定した高さ(height: 320px)を、そのままグラフの高さとして使うようになる
                // trueのままだと、Chart.jsが独自の比率計算を優先してしまい、想定外のサイズになることがある
                maintainAspectRatio: false,
            }
        });
    </script>
    @endif
</x-app-layout>

