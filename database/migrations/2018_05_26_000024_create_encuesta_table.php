<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEncuestaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('encuesta', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->integer('numeroAlumnos');
			$table->integer('numeroContestados')->nullable();
			$table->integer('idHorario')->unsigned();
			$table->double('puntajeFinal')->nullable();
			$table->timestamps();
	
	
			
			$table->foreign('idHorario')->references('id')->on('horario')->onUpdate('cascade')->onDelete('cascade');
			
			
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('encuesta');
	}

}
