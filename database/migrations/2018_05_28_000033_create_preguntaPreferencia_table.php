<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreguntaPreferenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('preguntaPreferencia', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->string('pregunta',500);
			$table->text('descripcion')->nullable();
			$table->boolean('estado');
			$table->timestamps();
				
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('preguntaPreferencia');
	}

}
