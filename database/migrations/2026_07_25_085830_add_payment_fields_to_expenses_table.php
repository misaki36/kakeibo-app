<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            // 支払期日：この日までに支払う必要がある日付
            // date()型を使い、未入力でも良いようnullable()を付ける
            // （すでに支払い済みの支出には期日が不要なため）
            $table->date('due_date')->nullable()->after('date');

            // 重要度：1〜3の数値で管理（1=低い、2=普通、3=高い、など運用ルールは後で決める）
            // unsignedTinyInteger：小さい整数専用の型（1〜3程度の値には十分）
            // default(2)：未指定の場合は「普通」を初期値にしておく
            $table->unsignedTinyInteger('priority')->default(2)->after('due_date');

            // 支払い済みフラグ：true=支払い済み、false=未払い
            // boolean型、default(false)で「登録した直後はまだ未払い」を初期値にする
            $table->boolean('is_paid')->default(false)->after('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            // up()で追加したカラムを、逆の順番で削除する
            // （down()は「マイグレーションを取り消したいとき」に使われる、いわば元に戻すボタン）
            $table->dropColumn(['due_date', 'priority', 'is_paid']);
        });
    }
};
