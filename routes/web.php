<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/faker','FakerController@home');
// Route::get('/test','DemoController@test');
// Route::get('/pdf','DemoController@pdf');
// Route::get('/reset','DemoController@pass_reset');


// Route::get('/', function () {
//     return view('welcome');
// });


//ruta_login

Route::get('/','Auth\LoginController@showLoginForm');


Auth::routes();

Route::get('/logout','Auth\LoginController@logout');
Route::get('/mapa','DemoController@mapa2');
Route::get('/mapa_visor','DemoController@visor_mapa');
Route::get('/mapa/analisis','DemoController@analisis_geometrico');


Route::group(['middleware'=>['auth',]],function(){
	Route::get('/home','ResguardoController@home');
	//resguardos
	Route::match(['get', 'post'],'/resguardo/formulario','ResguardoController@formulario');
	Route::get('/resguardo','ResguardoController@home');
	Route::post('/resguardo/save','ResguardoController@save');
	Route::post('/resguardo/print','ResguardoController@print');
	Route::post('/resguardo/search','SearchController@search_resguardo');
	Route::post('/resguardo/filter','ResguardoController@filter');
	Route::post('/resguardo/edit_form','ResguardoController@formulario2');
	//resguardos

	//activos
	Route::get('/activos','ActivoController@home');
	Route::post('/activos/form','ActivoController@formulario');
	Route::get('/activos/form/{id}','ActivoController@formulario_get');
	Route::post('/activos/save','ActivoController@guardar');
	Route::post('/activos/search_proveedor','ActivoController@search_proveedor');
	Route::post('/activos/search','ActivoController@search');
	Route::post('/activos/filter','ActivoController@filter');
	Route::get('/activos/tag/{id}','ActivoController@view_tag');
    Route::post('/activos/labels','ActivoController@imprimir_etiquetas');
    Route::post('/activo/upload','ActivoController@formulario_upload');
    Route::post('/activo/do_upload','ActivoController@upload');
    Route::post('/excel/load','ActivoController@carga_excel');
    Route::post('/proceso/load','ActivoController@obtener_detalle_proceso');
    Route::get('/excel/home','ActivoController@carga_home');
    Route::match(['get', 'post'],'/reverse_index','ActivoController@indice_inverso');
    Route::post('/indice_inverso/save','ActivoController@save_indice_inverso');
	//activos

	// nomina
	Route::post('/nomina/process','NominaController@procesa_nomina');
	Route::get('/nomina/upload','NominaController@subir_nomina');
	Route::get('/nomina/recupera','NominaController@recupera_recibo');
	Route::get('/nomina/test','NominaController@crypt_test');
	// nomina

	//Movimientos registro
	Route::get('/asignar_activo/{movimiento}','MovimientoController@asignar_activo');
	Route::get('/listamovs','MovimientoController@listamovs');
	Route::post('/guardarmovimiento','MovimientoController@elegir_guardar');
	//Movimientos bitacora
	Route::get('/bitacora/movimientos','BitacoraController@lista');
	Route::get('/bitacora/misactivos','BitacoraController@misactivos');
	Route::get('/bitacora/pdf','BitacoraController@convertirpdf');
	Route::post('/bitacora/consultardetalles','BitacoraController@consultardetalles');
});




//busqueda de funcionarios
Route::post('/funcionario/search','FuncionarioController@search');
//busqueda de funcionarios

//busqueda general
Route::post('/search/all','SearchController@search');
//busqueda general

//candado1
Route::group(['middleware'=>['auth',]],
	function(){


/*------------------- CARLOS -------------------*/

Route::get('/home', 'HomeController@index')->name('home');

//alta activos
		
		
		
		
		//tipo activos
		Route::get('/tipo_activo','Tipo_activoController@home');
		Route::post('/tipo_activo/formulario','Tipo_activoController@formulario');
		Route::get('/tipo_activo/formulario/{id}','Tipo_activoController@formulario_get');
		Route::post('/tipo_activo/save','Tipo_activoController@guardar');

		//Mail HOg
		Route::get('/email','testPHPMailer@index');

		//prueba jq
		Route::get('/prueba','PruebaController@home');
		Route::post('/prueba/formulario','PruebaController@formulario');
		Route::get('/prueba/formulario/{id}','PruebaController@formulario_get');
		Route::post('/prueba/save','PruebaController@guardar');


/*------------------- PEDRO -------------------*/




Route::get('/activo','AltaResguardoController@home');


Route::post('/usuario/ajax','UsuarioController@ajax');
Route::get('/usuario','UsuarioController@home');
Route::get('/usuario/formulario/{id}','UsuarioController@formulario_get');
Route::post('/usuario/formulario','UsuarioController@formulario');
Route::post('/usuario/save','UsuarioController@guardar');

Route::get('/rol','RolController@home');
Route::get('/rol/formulario/{id}','RolController@formulario_get');
Route::post('/rol/formulario','RolController@formulario');
Route::post('/rol/save','RolController@guardar');
// RUTA DINAMICA
Route::get('/rol/{idrol}','RolController@formulario_asignacion');
// RUTA PARA ASIGNAR LOS PERMISOS AL ROL
Route::post('/rol/permisos','RolController@asignar_permisos');

Route::get('/permiso','PermisoController@home');
Route::get('/permiso/formulario/{id}','PermisoController@formulario_get');
Route::post('/permiso/formulario','PermisoController@formulario');
Route::post('/permiso/save','PermisoController@guardar');

Route::get('/area','AreaController@home');
Route::get('/area/formulario/{id}','AreaController@formulario_get');
Route::post('/area/formulario','AreaController@formulario');
Route::post('/area/save','AreaController@guardar');

Route::post('/buscador','BuscadorController@buscar');
Route::get('/buscador','BuscadorController@buscar');	
Route::post('/buscador/activo_resguardo','BuscadorController@activo_resguardo');
Route::get('/buscador/activo_resguardo','BuscadorController@activo_resguardo');

	});



//RUTAS DE LA BITACORA DE MOVIMIENTOS


//PRUEBAS FAKER
Route::get('/bitacora/faker','BitacoraController@crear_datos');