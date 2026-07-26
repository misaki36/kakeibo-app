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
    Schema::create('categories', function (Blueprint $table) {
        $table->id(); // カテゴリID（主キー、AUTO_INCREMENT）
        $table->string('name', 50)->unique(); // カテゴリ名（重複不可）
        $table->timestamp('created_at')->useCurrent(); // 作成日時
        // categoriesはDB設計書上updated_atがないため、timestamps()は使わずcreated_atのみ定義
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
