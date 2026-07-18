<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * 家計簿でよく使う基本的なカテゴリを登録する
     */
    public function run(): void
    {
        // 家計簿アプリでよく使われる代表的なカテゴリ一覧
        $categories = ['食費', '日用品費', '交通費', '光熱費', '通信費', '娯楽費', '医療費', 'その他'];

        foreach ($categories as $name) {
            // firstOrCreate: 同じnameのデータが既にあれば何もせず、なければ新規作成する
            // 何度seederを実行しても、重複登録されないようにするための工夫
            Category::firstOrCreate(['name' => $name]);
        }
    }
}