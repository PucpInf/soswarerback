<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConvocatoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('convocatoria', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->string('titulo');
			$table->text('requisitos')->nullable();
			$table->string('puestoTrabajo',500)->nullable();
			$table->date('fecha_inicio_act')->nullable();
			$table->date('fecha_fin_post')->nullable();
			$table->string('link',500)->nullable();
			$table->text('estado');
			$table->integer('idSeccion')->nullable()->unsigned();
			$table->integer('cantidad')->nullable();
			$table->string('documentacion',1000)->nullable();
			$table->string('responsabilidades', 1000)->nullable();
			$table->string('beneficios',1000)->nullable();
			$table->date('fechaResultado')->nullable();
			$table->date('fechaPreSeleccion')->nullable();
			$table->string('evaluacion',1000)->nullable();
			$table->date('fechaCreacion')->nullable();		
			$table->timestamps();
				
					
			
			$table->foreign('idSeccion')->references('id')->on('seccion')->onUpdate('cascade')->onDelete('cascade');
			
			
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('convocatoria');
	}

}
