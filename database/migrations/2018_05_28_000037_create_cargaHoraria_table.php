<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargaHorariaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('cargaHoraria', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->integer('carga');
					
			$table->integer('idCiclo')->unsigned();
			$table->integer('idUsuario')->unsigned();
			$table->timestamps();
		
			
			$table->foreign('idCiclo')->references('id')->on('ciclo')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idUsuario')->references('id')->on('usuario')->onUpdate('cascade')->onDelete('cascade');
		
		
				
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('cargaHoraria');
	}

}
