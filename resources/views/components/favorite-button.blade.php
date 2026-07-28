{{-- @props：このコンポーネントが受け取る「部品の設定項目」を宣言する
     model：お気に入り対象のデータ（$expense または $income）
     toggleRoute：切り替え用のルート名（'expenses.favorite.toggle' または 'incomes.favorite.toggle'） --}}
@props(['model', 'toggleRoute'])

<div x-data="{ favorited: {{ $model->favoritedByUsers->contains(auth()->id()) ? 'true' : 'false' }} }">
    <button
        @click="
            fetch('{{ route($toggleRoute, $model) }}', {
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