<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRespuestaPreferenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('respuestaPreferencia', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->text('respuesta');
			
			$table->boolean('estado');
			$table->integer('idPregunta')->unsigned();
			$table->integer('idPreferencia')->nullable()->unsigned();
			$table->integer('idUsuario')->nullable()->unsigned();
			$table->timestamps();
			
			$table->foreign('idPregunta')->references('id')->on('preguntaPreferencia')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idPreferencia')->references('id')->on('preferenciaDictado')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idUsuario')->references('id')->on('usuario')->onUpdate('cascade')->onDelete('cascade');
		
		
	
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('respuestaPreferencia');
	}

}
