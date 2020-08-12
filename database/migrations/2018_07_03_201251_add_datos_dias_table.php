<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Models\Dia;

class AddDatosDiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dia = new Dia();
        $dia->dia = "Lunes";
        $dia->save();

        $dia = new Dia();
        $dia->dia = "Martes";
        $dia->save();

        $dia = new Dia();
        $dia->dia = "Miércoles";
        $dia->save();

        $dia = new Dia();
        $dia->dia = "Jueves";
        $dia->save();

        $dia = new Dia();
        $dia->dia = "Viernes";
        $dia->save();

        $dia = new Dia();
        $dia->dia = "Sábado";
        $dia->save();

        $dia = new Dia();
        $dia->dia = "Domingo";
        $dia->save();
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
