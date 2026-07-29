{{-- income：1件分の収入データ --}}
@props(['income'])

<tr class="border-b">
    <td class="py-2">{{ $income->date->format('Y-m-d') }}</td>
    <td class="py-2">¥{{ number_format($income->amount) }}</td>
    <td class="py-2">{{ Str::limit($income->memo, 20) }}</td>
    <td class="py-2">
        <x-favorite-button :model="$income" toggleRoute="incomes.favorite.toggle" />
    </td>
    <td class="py-2">
        <a href="{{ route('incomes.show', $income) }}" class="text-indigo-600">詳細</a>
        <a href="{{ route('incomes.edit', $income) }}" class="text-blue-600 mx-2">編集</a>
        <form action="{{ route('incomes.destroy', $income) }}" method="POST" class="inline"
              onsubmit="return confirm('本当に削除しますか？')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600">削除</button>
        </form>
    </td>
</tr>