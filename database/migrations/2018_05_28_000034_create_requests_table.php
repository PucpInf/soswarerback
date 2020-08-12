<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
		Schema::create('requests', function (Blueprint $table) {

			$table->increments('id')->unsigned();
				
		
	
		
			
			
		});
    }
	
	public function down(){
	
		Schema::dropIfExists('requests');
	}

}
