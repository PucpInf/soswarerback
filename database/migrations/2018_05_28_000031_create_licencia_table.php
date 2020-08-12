<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('licencia', function (Blueprint $table) {

			$table->increments('id');
			$table->text('descripcion')->nullable();
			$table->date('fecha_inicio');
			$table->date('fecha_fin');
			$table->text('observaciones')->nullable();
			$table->text('estado');
			$table->integer('idUsuario')->nullable()->unsigned();
			$table->integer('idTipoLicencia')->nullable()->unsigned();
			$table->string('lugar',200)->nullable();
			$table->string('motivo',1000)->nullable();
			$table->integer('idActividadLectiva')->nullable()->unsigned();
			$table->string('actividadNoLectiva',1000)->nullable();
			$table->date('fechaRespuesta')->nullable();
			$table->integer('departmentId')->nullable()->unsigned();
			$table->integer('sectionId')->nullable()->unsigned();
			$table->integer('requestType')->nullable();
			$table->string('actividadLectiva',2000)->nullable();
			$table->string('dedicacion',500)->nullable();
			$table->boolean('goceHaber');
			$table->integer('idCiclo')->nullable()->unsigned();
			$table->timestamps();
			
			
			$table->foreign('idUsuario')->references('id')->on('usuario')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idTipoLicencia')->references('id')->on('tipoLicencia')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idActividadLectiva')->references('id')->on('actividadLectiva')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('departmentId')->references('id')->on('departamento')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('sectionId')->references('id')->on('seccion')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idCiclo')->references('id')->on('ciclo')->onUpdate('cascade')->onDelete('cascade');
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('licencia');
	}

}
