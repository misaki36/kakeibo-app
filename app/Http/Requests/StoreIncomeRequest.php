<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncomeRequest extends FormRequest
{
    /**
     * このリクエストを実行する権限があるかどうか
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルール
     */
    public function rules(): array
    {
        return [
            // 金額は必須、整数、1円以上
            'amount' => 'required|integer|min:1',

            // メモは任意、1000文字まで
            'memo' => 'nullable|string|max:1000',

            // 収入日は必須、正しい日付形式であること
            'date' => 'required|date',
        ];
    }
}