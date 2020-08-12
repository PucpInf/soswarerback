<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Models\Usuario;

class AddAdministradorUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $usuario = new Usuario();
        $usuario->id = 20132716;
        $usuario->contrasena = bcrypt("12345");
        $usuario->fotoPerfil = "200.16.7.152/img/Usuarios/20181756.jpg";
        $usuario->areaInteres = "Macroeconomía";
        $usuario->especializacion = "Microeconomía";
        $usuario->idSeccion = 1;
        $usuario->idCategoria = 1;
        $usuario->idPersona = 63816291;
        $usuario->idTipo = 2;
        $usuario->correoPucp = "x.lin@pucp.pe";
        $usuario->nuevoUsuario = false;
        $usuario->save();
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
