<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDescargaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('descarga', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->text('razonDescarga');
			$table->integer('horasDescarga');
			$table->text('observacion')->nullable();
			$table->string('estado',200)->nullable();
			$table->integer('idUsuario')->unsigned();
			$table->integer('sectionId')->nullable()->unsigned();
			$table->integer('departmentId')->nullable()->unsigned();
			$table->integer('requestType')->nullable();
			$table->date('fechaCreacion')->nullable();
			$table->timestamps();
	
			
			$table->foreign('idUsuario')->references('id')->on('usuario')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('sectionId')->references('id')->on('seccion')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('departmentId')->references('id')->on('departamento')->onUpdate('cascade')->onDelete('cascade');
		
			
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('descarga');
	}

}
