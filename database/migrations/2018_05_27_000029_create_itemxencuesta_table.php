<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemxencuestaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('itemxencuesta', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->integer('idItem')->unsigned();
			$table->integer('idEncuesta')->unsigned();
			$table->double('puntaje')->nullable();
			$table->timestamps();
			
			
			$table->foreign('idItem')->references('id')->on('item')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idEncuesta')->references('id')->on('encuesta')->onUpdate('cascade')->onDelete('cascade');
		
			
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('itemxencuesta');
	}

}
