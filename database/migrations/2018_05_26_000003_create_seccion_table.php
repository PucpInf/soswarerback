<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('seccion', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->text('nombre');
			$table->integer('idDepartamento')->unsigned();
			$table->double('presupuesto')->nullable();
			$table->text('anexo')->nullable();
			$table->text('correo')->nullable();
			$table->timestamps();
			
			
			$table->foreign('idDepartamento')->references('id')->on('departamento')->onUpdate('cascade')->onDelete('cascade');
				
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('seccion');
	}

}
