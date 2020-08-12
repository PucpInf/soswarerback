<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
      Schema::create('dias', function (Blueprint $table) {
        $table->increments('id')->unsigned();
        $table->string('dia',100);
        $table->timestamps();
      });
    }
	
	public function down(){
	
		Schema::dropIfExists('dias');
	}

}
