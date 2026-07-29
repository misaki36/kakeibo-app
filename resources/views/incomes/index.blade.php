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

            <x-search-form route="incomes.index" />
            <x-date-range-filter route="incomes.index" />

            

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
                                <x-income-row :income="$income" />
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