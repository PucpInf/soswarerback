<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('persona', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->text('nombres');
			$table->text('apPaterno');
			$table->text('apMaterno')->nullable();
			$table->text('correo')->nullable();
			$table->text('telefono')->nullable();
			$table->date('fechaNacimiento')->nullable();
			$table->text('sexo')->nullable();
			$table->text('pais')->nullable();
			$table->timestamps();
		
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('persona');
	}
	
	

}
