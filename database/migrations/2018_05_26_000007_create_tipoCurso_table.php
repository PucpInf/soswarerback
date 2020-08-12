<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoCursoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('tipoCurso', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->integer('key');
			$table->text('nombre');
			$table->timestamps();
		
		});
			
    }
	
	public function down(){
	
		Schema::dropIfExists('tipoCurso');
	}

}
