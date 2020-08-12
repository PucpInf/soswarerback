<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividadLectivaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('actividadLectiva', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->text('tipoCurso');
			$table->integer('idCurso')->nullable()->unsigned();
			$table->integer('idDepartamento')->nullable()->unsigned();
			$table->integer('idUsuario')->nullable()->unsigned();
			$table->timestamps();
	
			
			$table->foreign('idCurso')->references('id')->on('curso')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idDepartamento')->references('id')->on('departamento')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idUsuario')->references('id')->on('usuario')->onUpdate('cascade')->onDelete('cascade');
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('actividadLectiva');
	}

}
