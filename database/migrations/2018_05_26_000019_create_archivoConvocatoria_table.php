<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivoConvocatoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('archivoConvocatoria', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->integer('idArchivo')->nullable()->unsigned();
			$table->integer('idConvocatoria')->nullable()->unsigned();
			$table->integer('idPostulante')->nullable()->unsigned();
			$table->timestamps();
			$table->unique(['idArchivo','idConvocatoria','idPostulante']);
			
			$table->foreign('idArchivo')->references('id')->on('archivo')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idConvocatoria')->references('id')->on('convocatoria')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idPostulante')->references('id')->on('postulante')->onUpdate('cascade')->onDelete('cascade');
			
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('archivoConvocatoria');
	}

}
