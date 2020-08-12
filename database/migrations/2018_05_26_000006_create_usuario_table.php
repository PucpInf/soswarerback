<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('usuario', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->text('contrasena')->nullable();
			$table->text('fotoPerfil')->nullable();
			$table->text('areaInteres')->nullable();
			$table->text('especializacion')->nullable();
			$table->integer('idSeccion')->nullable()->unsigned();
			$table->integer('idCategoria')->nullable()->unsigned();
			$table->integer('idPersona')->unsigned();
			$table->integer('idTipo')->unsigned();
			$table->text('correoPucp');
			$table->boolean('nuevoUsuario');
			$table->timestamps();
			
			$table->foreign('idPersona')->references('id')->on('persona')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idCategoria')->references('id')->on('tipoCategoria')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idSeccion')->references('id')->on('seccion')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('idTipo')->references('id')->on('tipoUsuario')->onUpdate('cascade')->onDelete('cascade');
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('usuario');
	}

}
