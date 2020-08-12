<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Models\TipoUsuario;

class AddDatosTipoUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tipo = new TipoUsuario();
        $tipo->nombre_tipo = "Profesor";
        $tipo->save();

        $tipo = new TipoUsuario();
        $tipo->nombre_tipo = "Administrador";
        $tipo->save();

        $tipo = new TipoUsuario();
        $tipo->nombre_tipo = "Coordinador de Sección";
        $tipo->save();

        $tipo = new TipoUsuario();
        $tipo->nombre_tipo = "Jefe de Departamento";
        $tipo->save();

        $tipo = new TipoUsuario();
        $tipo->nombre_tipo = "Asistente";
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
