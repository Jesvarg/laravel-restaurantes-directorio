<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

	class CreateRestaurantsTable extends Migration
	{
   	 public function up()
    	{
        	Schema::create('restaurants', function (Blueprint $table) {
            	$table->bigIncrements('id');
            	$table->string('name');
            	$table->string('address');
            	$table->string('phone')->nullable();
            	$table->unsignedBigInteger('user_id');
            	$table->text('description')->nullable();
            	$table->timestamps();

            	$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        	});
   	 }

    	public function down()
   	 {
        	Schema::dropIfExists('restaurants');
    	}
	}
