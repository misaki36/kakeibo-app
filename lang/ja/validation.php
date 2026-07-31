<?php

return [

    // それぞれのルールに違反したときのメッセージ
    'required' => ':attributeを入力してください。',
    'integer' => ':attributeは整数で入力してください。',
    'numeric' => ':attributeは数値で入力してください。',
    'min' => [
        'numeric' => ':attributeは:min以上の値にしてください。',
        'string' => ':attributeは:min文字以上で入力してください。',
        'file' => ':attributeは:minキロバイト以上のファイルにしてください。',
    ],
    'max' => [
        'numeric' => ':attributeは:max以下の値にしてください。',
        'string' => ':attributeは:max文字以内で入力してください。',
        'file' => ':attributeは:maxキロバイト以内のファイルにしてください。',
    ],
    'between' => [
        'numeric' => ':attributeは:min〜:maxの範囲で指定してください。',
    ],
    'date' => ':attributeには正しい日付を入力してください。',
    'date_format' => ':attributeは:format形式で入力してください。',
    'after' => ':attributeには:date以降の日付を指定してください。',
    'after_or_equal' => ':attributeには:date以降（当日含む）の日付を指定してください。',
    'before' => ':attributeには:dateより前の日付を指定してください。',
    'boolean' => ':attributeにはtrueかfalseを指定してください。',
    'string' => ':attributeは文字列で入力してください。',
    'image' => ':attributeには画像ファイルを指定してください。',
    'mimes' => ':attributeには:valuesタイプのファイルを指定してください。',
    'uploaded' => ':attributeのアップロードに失敗しました。ファイルサイズが大きすぎる可能性があります。',
    'exists' => '選択された:attributeは正しくありません。',
    'in' => '選択された:attributeは正しくありません。',
    'confirmed' => ':attributeと確認用の入力が一致しません。',
    'email' => ':attributeには正しいメールアドレス形式で入力してください。',
    'unique' => 'その:attributeはすでに使用されています。',

    // 項目名（フォームの項目を日本語で表示するための対応表）
    'attributes' => [
        'amount' => '金額',
        'category_id' => 'カテゴリ',
        'date' => '日付',
        'due_date' => '支払期日',
        'priority' => '重要度',
        'is_paid' => '支払い状況',
        'memo' => 'メモ',
        'receipt_image' => 'レシート画像',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'name' => '名前',
    ],

];
