<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorarioDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('horario_detalle', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->integer('idHorario')->unsigned();
			$table->integer('idDia')->unsigned();
			$table->time('horaInicio');
			$table->time('horaFin');
			$table->integer('horasDictado');
			$table->timestamps();
			
			
			$table->foreign('idHorario')->references('id')->on('horario')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idDia')->references('id')->on('dias')->onUpdate('cascade')->onDelete('cascade');
			
		
		
	
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('horarioDetalle');
	}

}
