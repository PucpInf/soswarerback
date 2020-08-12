<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreferenciaDictadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('preferenciaDictado', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->integer('idUsuario')->unsigned();
			$table->text('estado');
			$table->integer('idCiclo')->nullable()->unsigned();
			$table->integer('idCurso')->unsigned();
			$table->timestamps();
			
			
			
			$table->foreign('idUsuario')->references('id')->on('usuario')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idCiclo')->references('id')->on('ciclo')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idCurso')->references('id')->on('curso')->onUpdate('cascade')->onDelete('cascade');
	
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('preferenciaDictado');
	}

}
