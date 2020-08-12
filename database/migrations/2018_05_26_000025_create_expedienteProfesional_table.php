<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpedienteProfesionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('expedienteProfesional', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->string('puesto_de_trabajo',300);
			$table->string('empresa',300);
			$table->date('fecha_inicio')->nullable();
			$table->date('fecha_fin')->nullable();
			$table->integer('idUsuario')->unsigned();
			$table->string('pais',100)->nullable();
			$table->timestamps();
			
			$table->foreign('idUsuario')->references('id')->on('usuario')->onUpdate('cascade')->onDelete('cascade');
		
			
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('expedienteProfesional');
	}

}
