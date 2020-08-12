<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCicloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('ciclo', function (Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->string('ciclo',20)->unique();
			$table->timestamps();
		});			
    }
	
	public function down(){
	
		Schema::dropIfExists('ciclo');
	}

}
