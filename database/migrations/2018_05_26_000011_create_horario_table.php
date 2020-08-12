<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
    	Schema::create('horario', function (Blueprint $table) {

    		$table->increments('id',10)->unsigned();
    		$table->text('nombre');
    		$table->integer('idUsuario')->nullable()->unsigned();
    		$table->integer('idCiclo')->unsigned();
			$table->integer('idCurso')->unsigned();
			$table->integer('horas')->nullable();
			$table->timestamps();
						
			
    		$table->foreign('idUsuario')->references('id')->on('usuario')->onUpdate('cascade')->onDelete('cascade');
    		$table->foreign('idCiclo')->references('id')->on('ciclo')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idCurso')->references('id')->on('curso')->onUpdate('cascade')->onDelete('cascade');

        });
    }

	public function down(){
		Schema::dropIfExists('horario');
	}

}
