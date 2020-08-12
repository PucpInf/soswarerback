<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('item', function (Blueprint $table) {

			$table->increments('id')->unsigned();
      $table->string('titulo',300);
      $table->timestamps();
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('item');
	}

}
