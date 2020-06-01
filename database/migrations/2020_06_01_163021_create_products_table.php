<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('short_description');
            $table->string('description');
            $table->string('image_path');
            $table->unsignedInteger('price');
            $table->unsignedInteger('count_likes');
            $table->integer('product_category_id')->unsigned();
            $table->foreign('product_category_id')->references('id')->on('product_category');
            $table->integer('product_subcategory_id')->unsigned();
            $table->foreign('product_subcategory_id')->references('id')->on('product_subcategory');
            $table->softDeletes();
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
}
