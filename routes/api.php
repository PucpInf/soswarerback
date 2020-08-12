<?php

use Illuminate\Http\Request;

Route::group(['middleware' => 'cors'], function () {

  Route::post('/request/create','Api\RequestController@create');
  Route::post('/request','Api\RequestController@get');
  Route::post('/request/get','Api\RequestController@getRequest');
  Route::put('/request/response', 'Api\RequestController@responseRequest');
  Route::post('/request/getByType', 'Api\RequestController@getRequestByType');

  /****************************Apoyo Economico***********************************/
  Route::post('apoyoEconomico/nuevo', 'Api\ApoyoEconomicoController@registrar');
  Route::post('apoyoEconomico/actualizar/{idApoyoEconomico}', 'Api\ApoyoEconomicoController@actualizar');
  Route::post('apoyoEconomico/aprobacion', 'Api\ApoyoEconomicoController@aprobar');
  Route::get('apoyoEconomico', 'Api\ApoyoEconomicoController@get');
  //Route::post('apoyoEconomico/rechazo/{idApoyoEconomico}', 'Api\ApoyoEconomicoController@rechazar');
  /******************************************************************************/

  /****************************Usuario***********************************/
  Route::post('usuario/login', 'Api\UsuarioController@login');
  Route::post('usuario/registro', 'Api\UsuarioController@registro');
  Route::post('usuario/registroUsuarioContratado','Api\UsuarioController@registroUsuarioContratado');
  Route::get('usuario/listaProfesores','Api\UsuarioController@listaProfesores');
  Route::get('usuario/listaProfesoresEncuestas','Api\UsuarioController@listaProfesoresEncuestas');
  Route::get('usuario/datosFila/{id}','Api\UsuarioController@sacarDatosFila');
  Route::get('usuario/obtenerProfesor/{id}','Api\UsuarioController@obtenerProfesorEncuestas');
  Route::get('usuario/listaApoyosEconomicos/{idSeccion}','Api\UsuarioController@listaApoyosEconomicosSeccion');
  Route::get('usuario/muestraApoyosProfesor/{idUsuario}','Api\UsuarioController@muestraSolicitudesProfesor');
  Route::get('usuario/consultaDatos/{dni}','Api\UsuarioController@getDatosPersona');
  Route::post('usuario/editar','Api\UsuarioController@editarPerfil');
  Route::post('usuario/editarMiPerfil','Api\UsuarioController@editarMiPerfil');
  Route::get('usuario/getNombre/{idUsuario}','Api\UsuarioController@getNombreById');
  Route::post('usuario/cambiaPass','Api\UsuarioController@cambiarPassword');
  Route::get('usuario/getInfo/{idUsuario}','Api\UsuarioController@getInfoPerfil');
  Route::post('usuario/addPresupuesto','Api\UsuarioController@agregaPresupuesto');
  Route::post('usuario/restaurarPass','Api\UsuarioController@recuperarPass');
  /******************************************************************************/

  /****************************Archivos***********************************/
  Route::post('archivo/guardar', 'Api\ArchivoController@guardar');

  /******************************************************************************/
  /*************************Carga <Masiva>***************************************/
  Route::post('cargaMasiva/guardarArchivo', 'Api\ArchivoController@cargaMasivaHandler');
  /******************************************************************************/

  /****************************ciclo***********************************/
  Route::get('ciclo/GetCiclos','Api\CicloController@GetCiclos');
  Route::post('ciclo/AddCiclo', 'Api\CicloController@AddCiclo');
  Route::get('ciclo/GetCiclosAño/{ciclo}','Api\CicloController@GetCiclosAño');
  Route::get('ciclo/GetAñoCicloActual','Api\CicloController@GetAñoCicloActual');
  /******************************************************************************/

  /****************************Departamento***********************************/
  Route::get('departamento/GetDepartamentos','Api\DepartamentoController@GetDepartamentos');
  Route::post('departamento/Crear','Api\DepartamentoController@AddDepartamentos');
  Route::get('departamento/GetCursosHorarios/{depa}','Api\DepartamentoController@GetCursosHorarios');
  Route::get('departamento/GetProfesores/{depa}','Api\DepartamentoController@GetProfesores');
  Route::get('departamento/GetConcursoProfesores/{depa}','Api\DepartamentoController@GetConcursoProfesores');
  Route::post('departamento/addDepartamentos','Api\DepartamentoController@addDepa');
  Route::get('departamento/listaProfesores/{idDepartamento}','Api\DepartamentoController@listaProfesores');
  Route::get('departamento/GetCursillosHorarios/{idDepartamento}','Api\DepartamentoController@getHorariosCurso');
  Route::get('departamento/GetCursos/{idDepartamento}','Api\DepartamentoController@getCursos');
  /******************************************************************************/

  /****************************Seccion***********************************/
  Route::post('seccion/Crear','Api\SeccionController@AddSeccion');
  Route::get('seccion/GetCursos/{seccion}','Api\SeccionController@GetCursos');
  Route::get('seccion/GetCursillos/{seccion}','Api\SeccionController@GetCursillos');
  Route::post('seccion/addSecciones','Api\SeccionController@addSecciones');
  Route::get('seccion/listaProfesores/{idSeccion}','Api\SeccionController@listaProfesores');
  Route::get('seccion/GetCursosHorarios/{idSeccion}','Api\SeccionController@getHorariosCurso');
  /***************************************************************************/

  /****************************TipoCategoria***********************************/
  Route::get('categoria/GetCategorias','Api\TipoCategoriaController@GetCategorias');
  /******************************************************************************/
  /****************************CURSO***********************************/

  Route::post('course/userCourses', 'Api\CursoController@getUserCourses');
  Route::get('curso/GetCursosAno/{ano}','Api\CursoController@GetCursosAno');
  Route::get('curso/getInfoCurso/{idCurso}','Api\CursoController@getInfoCurso');

  Route::get('curso/GetHorariosPorCiclo/{idDepartamento?}/{idSeccion?}/{idCiclo?}','Api\CursoController@GetCursosCiclo');
  Route::get('curso/GetHorariosPorCursoCiclo/{cursoId}/{cicloId}/{departamentoId?}/{seccionId?}','Api\CursoController@GetCursosCursoCiclo');
  Route::get('curso/GetCursosGroupByCiclo/{cursoId}/{departamentoId?}/{seccionId?}','Api\CursoController@GetCursosGroupByCiclo');
  Route::get('curso/GetProfesoresGroupByCiclo/{profesorId}/{departamentoId?}/{seccionId?}','Api\CursoController@GetProfesoresGroupByCiclo');
  Route::get('curso/GetProfesoresCurso/{profesorId}/{departamentoId?}/{seccionId?}','Api\UsuarioController@GetProfesoresCurso');
  Route::get('curso/GetProfesoresCursoJP/{profesorId}/{departamentoId?}/{seccionId?}','Api\UsuarioController@GetProfesoresCursoJP');
  Route::get('curso/GetProfesoresCicloCurso/{idUsuario}/{idCiclo}','Api\UsuarioController@GetProfesoresCicloCurso');
  Route::get('curso/GetCursos','Api\CursoController@GetCursos');
  Route::get('curso/GetTipoCursosAno/{ano}/{tipoCurso}/{dep}','Api\CursoController@GetTipoCursosAno');
  Route::post('curso/AddCurso','Api\CursoController@AddCurso');

  /******************************************************************************/
  /****************************PreferenciaDictado***********************************/
  Route::post('PreferenciaDictado/AddPreferencia','Api\PreferenciaDictadoController@AddPreferencia');
  Route::get('PreferenciaDictado/GetProfesoresCurso/{curso}','Api\PreferenciaDictadoController@GetProfesoresCurso');
  Route::get('PreferenciaDictado/{idUsuario}','Api\PreferenciaDictadoController@GetPreferencias');
  Route::get('PreferenciaDictado/getPreferencias/{idSeccion}','Api\PreferenciaDictadoController@getPreferenciasSeccion');
  Route::get('PreferenciaDictado/DeletePreferencia/{id}','Api\PreferenciaDictadoController@DeletePreferencia');
  /******************************************************************************/
  /****************************ENCUESTAS***********************************/
  Route::get('Encuesta/GetEncuestasCicloCurso/{ciclo}/{curso}','Api\EncuestaController@GetEncuestas');
  Route::post('Encuesta/file','Api\EncuestaController@file');
  Route::get('Encuesta/GetPuntajesByFacultad/{idDepartamento}/{idCiclo?}','Api\EncuestaController@puntajesByFacultad');
  Route::get('Encuesta/GetPuntajesByCategoria/{idDepartamento}/{idCiclo?}','Api\EncuestaController@puntajesByCategoria');
  Route::get('Encuesta/GetPuntajesByDedicacion/{idDepartamento}/{idCiclo?}','Api\EncuestaController@puntajesByDedicacion');
  Route::get('Encuesta/GetPuntajesByCurso/{idDepartamento}/{idCiclo?}','Api\EncuestaController@puntajesByCurso');
  Route::get('Encuesta/GetAllEncuestas','Api\EncuestaController@GetAllEncuestas');
  Route::get('Encuesta/GetPuntajesBySeccion/{idDepartamento}/{idCiclo?}','Api\EncuestaController@puntajesBySeccion');

  /****************************Convocatoria***********************************/
  Route::get('convocatoria/getDatos','Api\ConvocatoriaController@getDatosConvocatoria');
  Route::post('convocatoria/registrar','Api\ConvocatoriaController@registraConvocatoria');
  Route::post('convocatoria/registraPostulante','Api\UsuarioController@registraPostulante');
  Route::get('convocatoria/GetPostulantes/{dep}','Api\ConvocatoriaController@GetPostulantesDepartamento');
  Route::get('convocatoria/listaConvocatorias/{idDepartamento}','Api\ConvocatoriaController@listaConvocatoriasActivas');
  Route::get('convocatoria/listaConvocatoriasSeccion/{idSeccion}','Api\ConvocatoriaController@listaConvocatoriasSeccion');
  Route::get('convocatoria/getPostulantesConvocatoria/{idConvocatoria}','Api\ConvocatoriaController@getPostulantesPorConvocatoria');
  Route::post('convocatoria/seleccion','Api\ConvocatoriaController@seleccion');
  /****************************Concurso de Nivel***********************************/
  Route::get('concursoNivel/getConcursosSeccion/{idDepartamento}','Api\ConcursoNivelController@getConcursosActivos');
  Route::post('concursoNivel/postConcurso','Api\ConcursoNivelController@guardarConcursoNivel');
  Route::post('concursoNivel/postular','Api\ConcursoNivelProfeController@guardarPostulante');
  Route::post('concursoNivel/aprobar','Api\ConcursoNivelController@aprobarPostulacion');
  /****************************Asignacion de Carga***********************************/
  Route::post('asignacionCarga/asignar','Api\HorarioController@asignarHorario');
  /******************************************************************************/
  /****************************HORARIO***********************************/
  Route::get('horario/GetCursosProfesorCiclo/{idUsuario}/{idCiclo}','Api\HorarioController@GetCursosProfesorCiclo');
  Route::get('listHorarios/{idUsuario}/','Api\HorarioController@list');
  Route::get('listaJP/{idUsuario}','Api\HorarioController@listar');
  /******************************************************************************/
  /****************************Licencias***********************************/
  Route::get('licencia/getLicenciasUsuario/{idUsuario}/{idCiclo}','Api\LicenseController@licenciasUsuario');
  /******************************************************************************/
  /****************************CargaHoraria***********************************/
  Route::get('CargaHoraria/{idUsuario}/{idCiclo}','Api\CargaHorariaController@GetCarga');
  /******************************************************************************/
  /****************************TipoCategoria***********************************/
  Route::get('TipoUsuario/GetTipoUsuario','Api\TipoUsuarioController@GetTipoUsuario');
  /****************************Facultad***********************************/
  Route::get('facultad/GetFacultades','Api\FacultadController@GetFacultades');
  /******************************************************************************/
  Route::post('usuario/loginGoogle/callback','Api\UsuarioController@googleLoginCallback');
});

Route::namespace('Api')->prefix('investigacion')->group(function() {
  Route::post('new', 'InvestigacionController@new');
  Route::get('get', 'InvestigacionController@get');
});
