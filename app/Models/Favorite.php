<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'favoritable_type',
        'favoritable_id',
    ];

    // このお気に入りは、どのユーザーのものか
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // このお気に入りは、何（支出 or 収入）に対するものか
    // morphTo()：favoritable_type・favoritable_idを見て、自動的にExpenseかIncomeを判断してくれる
    public function favoritable()
    {
        return $this->morphTo();
    }
}