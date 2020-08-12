<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRespuestaConcursoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('respuestaConcurso', function (Blueprint $table) {

			$table->increments('id')->unsigned();
      $table->boolean('estado');
      $table->timestamps();
			
			
			
		
	
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('respuestaConcurso');
	}

}
