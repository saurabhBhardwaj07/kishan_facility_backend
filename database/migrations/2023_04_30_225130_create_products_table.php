<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_category_id')->references('id')->on('product_categories')->onDelete('cascade');
            $table->string('name', 100);
            $table->smallInteger('quantity');
            $table->string('size', 10);
            $table->double('price', 10, 2);
            $table->tinyInteger('discount')->nullable();
            $table->text('description');
            $table->text('composition');
            $table->text('uses');
            $table->enum('status', ['1', '0'])->nullable()->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
