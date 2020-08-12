<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Models\TipoLicencia;

class AddDatosTipoLicenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tipo = new TipoLicencia();
        $tipo->descripcion = "Con goce de haber";
        $tipo->save();

        $tipo = new TipoLicencia();
        $tipo->descripcion = "Sin goce de haber";
        $tipo->save();
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
