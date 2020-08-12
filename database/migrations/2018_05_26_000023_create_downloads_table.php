<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('downloads', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			
			
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('downloads');
	}

}
