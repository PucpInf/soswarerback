<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacultadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('facultad', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->string('nombreFacultad',200)->nullable();
			$table->timestamps();
		
		});	
    }
	
	public function down(){
		Schema::dropIfExists('facultad');
	}
		

}
