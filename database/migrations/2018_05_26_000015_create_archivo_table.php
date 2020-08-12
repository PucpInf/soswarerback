<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('archivo', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->string('nombreArchivo',100);
			$table->string('urlArchivo',800);
			$table->string('extension',10);
			$table->timestamps();
		});
			
		
    }
	
	public function down(){
	
		Schema::dropIfExists('archivo');
	}

}
