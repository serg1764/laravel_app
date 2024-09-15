<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // название товара
            $table->string('title'); // заголовок товара
            $table->string('url'); // URL товара
            $table->text('description')->nullable(); // описание товара
            $table->text('content')->nullable(); // контент товара
            $table->decimal('price', 8, 2); // цена товара
            $table->integer('quantity'); // количество товара на складе
            $table->string('sku')->unique(); // артикул товара
            $table->unsignedBigInteger('category_id'); // категория товара
            $table->string('image')->nullable(); // путь к изображению товара
            $table->boolean('inactive')->default(false); // статус товара (по умолчанию неактивен)

            $table->timestamps(); // создаёт поля created_at и updated_at

            // связь с таблицей categories
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
