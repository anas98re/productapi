<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('views');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
            $table->string('pro_name');
            $table->string('price');
            // $table->string('current_price');
            // $table->string('pro_image');
            $table->string('title');
            $table->string('url');
            $table->string('pro_expiration_Date');
            $table->string('pro_Category');
            $table->string('pro_phone');
            $table->string('pro_quantity');
            // $table->string('pro_disCount1');
            // $table->string('pro_disCount2');
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
