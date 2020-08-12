<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Models\Facultad;

class AddDatosFacultadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $facu = new Facultad();
		$facu->nombreFacultad = "Facultad de Ciencias e Ingeniería";
		$facu->save();
		
		$facu = new Facultad();
		$facu->nombreFacultad = "Estudios Generales Letras";
		$facu->save();
		
		$facu = new Facultad();
		$facu->nombreFacultad = "Facultad de Ciencias Sociales";
		$facu->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
