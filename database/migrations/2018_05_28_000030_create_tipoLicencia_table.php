<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatetipoLicenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('tipoLicencia', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->text('descripcion');
			$table->timestamps();
					
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('tipoLicencia');
	}

}
