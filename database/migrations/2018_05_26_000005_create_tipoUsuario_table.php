<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('tipoUsuario', function (Blueprint $table) {

			$table->increments('id')->unsigned();
            $table->text('nombre_tipo');
			$table->timestamps();
				
		
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('tipoUsuario');
	}

}
