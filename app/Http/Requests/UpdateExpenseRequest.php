<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    /**
     * このリクエストを実行する権限があるかどうか
     *
     * 更新の権限チェックはController側で $this->authorize('update', $expense)
     * を使ってPolicyに任せているので、ここでは true を返すだけでOK
     * （trueにしておかないと、そもそもリクエスト自体が403で弾かれてしまう）
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルール（StoreExpenseRequestとほぼ同じ内容）
     */
    public function rules(): array
    {
        return [
            'category_id' => 'nullable|exists:categories,id',
            'amount' => 'required|integer|min:1',
            'memo' => 'nullable|string|max:1000',
            'date' => 'required|date',

            // 支払期日：任意入力、日付形式であること
            'due_date' => 'nullable|date',

            // 重要度：任意入力、1〜3の整数のみ許可
            'priority' => 'nullable|integer|between:1,3',

            // 支払い済みフラグ：チェックボックスのON/OFFを送る想定
            'is_paid' => 'nullable|boolean',

            // 最大サイズを5MB(5120KB)に緩和。スマホで撮影したレシート写真は2〜3MB程度になることも多いため
            'receipt_image' => 'nullable|image|max:5120',
        ];
    }
}