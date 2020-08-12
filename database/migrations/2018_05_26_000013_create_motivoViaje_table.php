<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMotivoViajeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('motivoViaje', function (Blueprint $table) {

			$table->increments('id');
      $table->text('descripcion');
      $table->timestamps();
			
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('motivoViaje');
	}

}
