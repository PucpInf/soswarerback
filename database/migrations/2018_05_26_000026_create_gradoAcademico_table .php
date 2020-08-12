<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradoAcademicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('gradoAcademico', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->string('nombre',300);
			$table->string('institucion',300);
			$table->integer('idUsuario')->nullable()->unsigned();
			$table->timestamps();
		
			$table->foreign('idUsuario')->references('id')->on('usuario')->onUpdate('cascade')->onDelete('cascade');
		
			
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('gradoAcademico');
	}

}
