<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcursoNivelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('concursoNivel', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			
			$table->string('titulo',500);
			$table->text('descripcion')->nullable();
			$table->date('fecha_inicio')->nullable();
			$table->date('fecha_fin')->nullable();
			$table->boolean('estado')->nullable();
			$table->string('nivel',50)->nullable();
			$table->integer('idUsuario')->unsigned();
			$table->integer('idDepartamento')->nullable()->unsigned();
			$table->timestamps();
			
			$table->foreign('idUsuario')->references('id')->on('usuario')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idDepartamento')->references('id')->on('departamento')->onUpdate('cascade')->onDelete('cascade');	
			
					
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('concursoNivel');
	}

}
