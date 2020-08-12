<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoCategoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('tipoCategoria', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->string('nombre_categoria',200)->nullable();
			$table->timestamps();
		
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('tipoCategoria');
	}

}
