<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    /**
     * このリクエストを実行する権限があるかどうか
     * ログイン済みユーザーなら誰でも支出を登録できるので true にする
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
            // exists:categories,id → 送られてきたcategory_idが、実際にcategoriesテーブルに存在するか確認
            // nullable → カテゴリ未選択でもOK(DB設計書通り)
            'category_id' => 'nullable|exists:categories,id',

            // 金額は必須、整数、1円以上
            'amount' => 'required|integer|min:1',

            // メモは任意、1000文字まで
            'memo' => 'nullable|string|max:1000',

            // 支出日は必須、正しい日付形式であること
            'date' => 'required|date',
        ];
    }
}