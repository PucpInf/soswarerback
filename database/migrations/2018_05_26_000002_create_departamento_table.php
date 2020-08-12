<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('departamento', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->text('nombre');
			$table->double('presupuesto')->nullable();
			$table->text('anexo');
			$table->text('correo')->nullable();
			$table->timestamps();
				
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('departamento');
	}

}
