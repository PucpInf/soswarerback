<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcursoPorProfesorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('concursoPorProfesor', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->integer('idUsuario')->unsigned();
			$table->integer('idConcurso')->nullable()->unsigned();
			
			$table->text('estado');
			$table->integer('requestType');
			$table->date('fechaRegistro');
			$table->timestamps();
			
			
			$table->foreign('idConcurso')->references('id')->on('concursoNivel')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idUsuario')->references('id')->on('usuario')->onUpdate('cascade')->onDelete('cascade');
		
		
	
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('concursoPorProfesor');
	}

}
