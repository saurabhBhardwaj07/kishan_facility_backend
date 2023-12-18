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
        Schema::create('crops', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('crop_category_id')->references('id')->on('crop_categories')->onDelete('cascade');
            $table->string('name', 100);
            $table->text('introduction');
            $table->text('climate')->nullable();
            $table->text('soil')->nullable();
            $table->text('season')->nullable();
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
        Schema::dropIfExists('crops');
    }
};
