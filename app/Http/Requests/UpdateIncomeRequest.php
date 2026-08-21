<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIncomeRequest extends FormRequest
{
    /**
     * 更新の権限チェックはController側でPolicyに任せているので、ここではtrueでOK
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
            'amount' => 'required|integer|min:1',
            'memo' => 'nullable|string|max:1000',
            'date' => 'required|date',
        ];
    }
}