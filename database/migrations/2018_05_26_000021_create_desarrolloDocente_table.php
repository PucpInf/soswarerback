<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesarrolloDocenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('desarrolloDocente', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->string('puesto_de_trabajo',200);
			$table->date('fecha_inicio')->nullable();
			$table->date('fecha_fin')->nullable();
			$table->integer('idDepartamento')->nullable()->unsigned();
			$table->integer('idCategoria')->nullable()->unsigned();
			$table->integer('idUsuario')->nullable()->unsigned();
			$table->timestamps();
			
			$table->foreign('idDepartamento')->references('id')->on('departamento')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idCategoria')->references('id')->on('tipoCategoria')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idUsuario')->references('id')->on('usuario')->onUpdate('cascade')->onDelete('cascade');
			
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('desarrolloDocente');
	}

}
