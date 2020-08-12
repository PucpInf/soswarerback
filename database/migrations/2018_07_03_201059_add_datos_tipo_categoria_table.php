<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Models\TipoCategoria;

class AddDatosTipoCategoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tipo = new TipoCategoria();
        $tipo->nombre_categoria = "Contratado";
        $tipo->save();

        $tipo = new TipoCategoria();
        $tipo->nombre_categoria = "Auxiliar";
        $tipo->save();

        $tipo = new TipoCategoria();
        $tipo->nombre_categoria = "Asociado";
        $tipo->save();

        $tipo = new TipoCategoria();
        $tipo->nombre_categoria = "Principal";
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
