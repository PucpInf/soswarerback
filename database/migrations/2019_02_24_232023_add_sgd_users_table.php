<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Models\Usuario;

class AddSgdUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $usuario = new Usuario();
        $usuario->id = 20125187;
        $usuario->contrasena = bcrypt("12345");
        $usuario->fotoPerfil = "200.16.7.152/img/Usuarios/20181756.jpg";
        $usuario->areaInteres = "Desarrollo web";
        $usuario->especializacion = "Backend";
        $usuario->idSeccion = 1;
        $usuario->idCategoria = 1;
        $usuario->idPersona = 70662737;
        $usuario->idTipo = 2;
        $usuario->correoPucp = "acalvo@pucp.pe";
        $usuario->nuevoUsuario = false;
        $usuario->idGoogle="101469233945819046108";
        $usuario->save();

        $usuario = new Usuario();
        $usuario->id = 20135298;
        $usuario->contrasena = bcrypt("12345");
        $usuario->fotoPerfil = "200.16.7.152/img/Usuarios/20181756.jpg";
        $usuario->areaInteres = "Todo";
        $usuario->especializacion = "Todo";
        $usuario->idSeccion = 1;
        $usuario->idCategoria = 1;
        $usuario->idPersona = 72839482;
        $usuario->idTipo = 2;
        $usuario->correoPucp = "a20135298@pucp.pe";
        $usuario->nuevoUsuario = false;
        $usuario->save();

        $usuario = new Usuario();
        $usuario->id = 20125498;
        $usuario->contrasena = bcrypt("12345");
        $usuario->fotoPerfil = "200.16.7.152/img/Usuarios/20181756.jpg";
        $usuario->areaInteres = "Todo";
        $usuario->especializacion = "Todo";
        $usuario->idSeccion = 1;
        $usuario->idCategoria = 1;
        $usuario->idPersona = 93849381;
        $usuario->idTipo = 2;
        $usuario->correoPucp = "jpsullon@pucp.pe";
        $usuario->nuevoUsuario = false;
        $usuario->save();

        $usuario = new Usuario();
        $usuario->id = 20114878;
        $usuario->contrasena = bcrypt("12345");
        $usuario->fotoPerfil = "200.16.7.152/img/Usuarios/20181756.jpg";
        $usuario->areaInteres = "Todo";
        $usuario->especializacion = "Todo";
        $usuario->idSeccion = 1;
        $usuario->idCategoria = 1;
        $usuario->idPersona = 83728191;
        $usuario->idTipo = 2;
        $usuario->correoPucp = "jbejarano@pucp.pe";
        $usuario->nuevoUsuario = false;
        $usuario->save();

        $usuario = new Usuario();
        $usuario->id = 20146278;
        $usuario->contrasena = bcrypt("12345");
        $usuario->fotoPerfil = "200.16.7.152/img/Usuarios/20181756.jpg";
        $usuario->areaInteres = "Todo";
        $usuario->especializacion = "Todo";
        $usuario->idSeccion = 1;
        $usuario->idCategoria = 1;
        $usuario->idPersona = 72839281;
        $usuario->idTipo = 2;
        $usuario->correoPucp = "gsanchez@pucp.pe";
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
