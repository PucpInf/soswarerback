<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Models\MotivoViaje;

class AddDatosMotivosTablilla extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $motivo = new MotivoViaje();
		$motivo->descripcion = "Estudios de mayor duración";
		$motivo->save();
		
		$motivo = new MotivoViaje();
		$motivo->descripcion = "Dictado de Cursos";
		$motivo->save();
		
		$motivo = new MotivoViaje();
		$motivo->descripcion = "Profesor visitante";
		$motivo->save();
		
		$motivo = new MotivoViaje();
		$motivo->descripcion = "Gestión Administrativa";
		$motivo->save();
		
		$motivo = new MotivoViaje();
		$motivo->descripcion = "Visita por invitación";
		$motivo->save();
		
		$motivo = new MotivoViaje();
		$motivo->descripcion = "Otros";
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
