<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApoyoEconomicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('apoyoEconomico', function (Blueprint $table) {

			$table->increments('id');
			$table->double('montoSolicitado');
			$table->date('fechaViaje');
			$table->date('fechaEvento');
			$table->text('observacion')->nullable();
			$table->string('estado',50);
			$table->integer('idUsuario')->unsigned();
			$table->double('montoAprobado')->nullable();
			$table->string('tipoPersonal',50)->nullable();
			$table->integer('idMotivo')->unsigned();
			$table->string('moneda',20);
			$table->date('fechaRespuesta')->nullable();
			$table->integer('boleto')->nullable();
			$table->integer('inscripcion')->nullable();
			$table->integer('hospedaje')->nullable();
			$table->integer('assistCard')->nullable();
			$table->integer('alimentosMovilidad')->nullable();
			$table->integer('impuestos')->nullable();
			$table->text('file')->nullable();
			
			$table->integer('requestType');
			$table->integer('sectionId')->unsigned();
			$table->integer('departmentId')->nullable()->unsigned();
			$table->integer('activityId')->nullable()->unsigned();
			$table->timestamps();
			
	
			
			$table->foreign('idUsuario')->references('id')->on('usuario')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idMotivo')->references('id')->on('motivoViaje')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('sectionId')->references('id')->on('seccion')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('departmentId')->references('id')->on('departamento')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('activityId')->references('id')->on('actividadLectiva')->onUpdate('cascade')->onDelete('cascade');
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('apoyoEconomico');
	}

}
