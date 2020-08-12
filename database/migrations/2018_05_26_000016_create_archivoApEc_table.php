<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivoApEcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('archivoApEc', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->integer('idArchivo')->unsigned();
			$table->integer('idApoyoEconomico')->unsigned();
			$table->timestamps();
			$table->unique(['idArchivo','idApoyoEconomico']);
			
			$table->foreign('idArchivo')->references('id')->on('archivo')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idApoyoEconomico')->references('id')->on('apoyoEconomico')->onUpdate('cascade')->onDelete('cascade');
			
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('archivoApEc');
	}

}
