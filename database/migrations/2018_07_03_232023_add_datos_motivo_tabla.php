<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Models\MotivoViaje;

class AddDatosMotivoTabla extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $motivo = new MotivoViaje();
		$motivo->descripcion = "Evento Académico";
		$motivo->save();
		
		$motivo = new MotivoViaje();
		$motivo->descripcion = "Evento Deportivo";
		$motivo->save();
		
		$motivo = new MotivoViaje();
		$motivo->descripcion = "Actividad Cultural";
		$motivo->save();
		
		$motivo = new MotivoViaje();
		$motivo->descripcion = "Asistencia a cursos cortos";
		$motivo->save();
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
