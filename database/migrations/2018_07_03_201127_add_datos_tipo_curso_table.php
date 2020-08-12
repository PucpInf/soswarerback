<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Models\TipoCurso;

class AddDatosTipoCursoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $tipo = new TipoCurso();
        $tipo->key = 1;
        $tipo->nombre = "Obligatorio";
        $tipo->save();

        $tipo = new TipoCurso();
        $tipo->key = 0;
        $tipo->nombre = "Electivo";
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
