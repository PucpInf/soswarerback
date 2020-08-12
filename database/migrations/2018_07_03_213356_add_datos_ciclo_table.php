<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Models\Ciclo;

class AddDatosCicloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ciclo = new Ciclo();
        $ciclo->ciclo = "2017-1";
        $ciclo->save();

        $ciclo = new Ciclo();
        $ciclo->ciclo = "2017-2";
        $ciclo->save();

        $ciclo = new Ciclo();
        $ciclo->ciclo = "2018-1";
        $ciclo->save();

        $ciclo = new Ciclo();
        $ciclo->ciclo = "2018-2";
        $ciclo->save();

        $ciclo = new Ciclo();
        $ciclo->ciclo = "2019-1";
        $ciclo->save();

        $ciclo = new Ciclo();
        $ciclo->ciclo = "2019-2";
        $ciclo->save();
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
