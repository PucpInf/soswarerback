<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCursoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('curso', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->string('codigo',255);
			$table->text('nombre');
			$table->integer('idSeccion')->nullable()->unsigned();
			$table->integer('idTipoCurso')->nullable()->unsigned();
			$table->double('creditosTot')->nullable();
			$table->double('credPrac')->nullable();
			$table->double('credTeor')->nullable();
			$table->integer('idFacultad')->unsigned();
			$table->timestamps();
			
			$table->foreign('idSeccion')->references('id')->on('seccion')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idFacultad')->references('id')->on('facultad')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idTipoCurso')->references('id')->on('tipoCurso')->onUpdate('cascade')->onDelete('cascade');
		});	
    }
	
	public function down(){
	
		Schema::dropIfExists('curso');
	}

}
