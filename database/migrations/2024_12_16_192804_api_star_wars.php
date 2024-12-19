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
        Schema::create('api_star_wars', function (Blueprint $table) {
            $table->id(); // Создает столбец id как первичный ключ
            $table->integer('iid'); // Создает столбец iid
            $table->string('name'); // Создает столбец name
            $table->string('url'); // Создает столбец url
            $table->json('json'); // Создает столбец json
            $table->timestamps(); // Создает столбцы created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_star_wars');
    }
};
