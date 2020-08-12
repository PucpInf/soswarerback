<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostulanteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('postulante', function (Blueprint $table) {

			$table->increments('id');
			$table->integer('idPersona')->unsigned();
			$table->integer('idConvocatoria')->unsigned();
			$table->text('estado');
			$table->timestamps();
			$table->unique(['idPersona','idConvocatoria']);
			
			
			$table->foreign('idPersona')->references('id')->on('persona')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idConvocatoria')->references('id')->on('convocatoria')->onUpdate('cascade')->onDelete('cascade');
		
			
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('postulante');
	}

}
