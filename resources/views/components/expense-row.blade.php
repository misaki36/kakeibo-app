{{-- expense：1件分の支出データ --}}
@props(['expense'])

<tr class="border-b">
    {{-- format('Y-m-d')で時刻部分を省いて、日付だけを表示する --}}
    <td class="py-2">{{ $expense->date->format('Y-m-d') }}</td>
    {{-- カテゴリが未設定(null)の場合は「未分類」と表示 --}}
    <td class="py-2">{{ $expense->category->name ?? '未分類' }}</td>
    {{-- number_format()で金額に3桁区切りのカンマをつける --}}
    <td class="py-2">¥{{ number_format($expense->amount) }}</td>
    {{-- Str::limit()でメモが長い場合に省略表示 --}}
    <td class="py-2">{{ Str::limit($expense->memo, 20) }}</td>
    <td class="py-2">
        {{-- due_dateが設定されていれば日付を表示、なければ「―」を表示 --}}
        {{ $expense->due_date ? $expense->due_date->format('Y-m-d') : '―' }}
    </td>
    <td class="py-2">
        {{-- priorityの数値(1〜3)に応じて、色分けしたラベルを表示 --}}
        {{ $expense->priority }}
    </td>
    <td class="py-2">
        {{-- is_paidがtrueなら「支払い済み」、falseなら「未払い」を表示 --}}
        @if ($expense->is_paid)
            <span class="text-green-600">支払い済み</span>
        @else
            <span class="text-red-600">未払い</span>
        @endif
    </td>
    <td class="py-2">
        {{-- receipt_imageが設定されていれば小さいサムネイル画像を表示、なければ「―」 --}}
        @if ($expense->receipt_image)
            <img src="{{ route('expenses.receipt-image', $expense) }}" alt="レシート画像"
                 class="w-12 h-12 object-cover rounded border border-gray-200">
        @else
            ―
        @endif
    </td>
    <td class="py-2">
        <x-favorite-button :model="$expense" toggleRoute="expenses.favorite.toggle" />
    </td>
    <td class="py-2">
        <a href="{{ route('expenses.show', $expense) }}" class="text-indigo-600">詳細</a>
        <a href="{{ route('expenses.edit', $expense) }}" class="text-blue-600 mx-2">編集</a>
        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline"
              onsubmit="return confirm('本当に削除しますか？')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600">削除</button>
        </form>
    </td>
</tr>