<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestigacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('investigacion', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->string('titulo',300);
			$table->string('abstract',500)->nullable();
			$table->string('indicador_calidad',20)->nullable();
			$table->string('codigo_validacion',10)->nullable();
			$table->string('otros_autores',500)->nullable();
			$table->string('link',500)->nullable();
			$table->date('fecha_inicio')->nullable();
			$table->date('fecha_fin')->nullable();
			$table->integer('idUsuario')->nullable()->unsigned();
			$table->timestamps();
		
			
			$table->foreign('idUsuario')->references('id')->on('usuario')->onUpdate('cascade')->onDelete('cascade');
		
			
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('investigacion');
	}

}
