<?php

use App\Http\Controllers\CatalogosController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MovimientosController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReportesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view ('welcome');
});

Route::get('/', function () {
    return view('home', ["breadcrumbs" => []]);	
});

//Empleados
Route::get("/catalogos/empleados", [CatalogosController::class, 'empleadosGet']);
Route::get ("/catalogos/empleados/agregar", [CatalogosController::class, 'empleadosAgregarGet']);
Route::post ("/catalogos/empleados/agregar", [CatalogosController::class, 'empleadosAgregarPost']);

//Puestos
Route::get ("/catalogos/puestos",[CatalogosController::class,'puestosGet']);
Route::get ("/catalogos/puestos/agregar",[CatalogosController::class,'puestosAgregarGet']);
Route::post ("/catalogos/puestos/agregar",[CatalogosController::class,'puestosAgregarPost']);

//EmpleadosPuestos
Route::get ("/empleados/{id}/puestos", [CatalogosController::class, 'empleadosPuestosGet']) -> where ("id", "[0-9]+");
Route::get ("/empleados/{id}/puestos/cambiar", [CatalogosController::class, 'empleadosPuestosCambiarGet']) -> where ("id", "[0-9]+");
Route::post ("/empleados/{id}/puestos/cambiar", [CatalogosController::class, 'empleadosPuestosCambiarPost']) -> where ("id", "[0-9]+");

//Prestamos
Route::get ("/movimientos/prestamos", [MovimientosController::class, 'prestamosGet']);
Route::get ("/movimientos/prestamos/agregar", [MovimientosController::class, "prestamosAgregarGet"]);
Route::post ("/movimientos/prestamos/agregar", [MovimientosController::class, "prestamosAgregarPost"]);

//Resumen
Route::get('/abonos/resumen', [CatalogosController::class, 'resumen'])->name('abonos.resumen');

//Abonos
Route::get("/prestamos/{prest}/abonos", [MovimientosController::class, "abonosGet"]) -> where("prest", "\\d+");
Route::get("/prestamos/{prest}/abonos/agregar", [MovimientosController::class, "abonosAgregarGet"]) -> where("prest", "\\d+");
Route::post("/prestamos/{prest}/abonos/agregar", [MovimientosController::class, "abonosAgregarPost"]) -> where("prest", "\\d+");

//EmpleadosPrestamo
Route::get ('/empleados/{id}/prestamos', [MovimientosController::class, "empleadosPrestamosGet"]) -> where ("id", "[0-9]+");
Route::get ('/reportes', [ReportesController::class, "indexGet"]);

//Prestamos
Route::get ('/reportes/prestamos', [ReportesController::class, "prestamosGet"]);
Route::get ('/reportes/prestamos-activos', [ReportesController::class, "prestamosActivosGet"]);

//Reportes
Route::get('/reportes/matriz-abonos', [ReportesController::class, "matrizAbonosGet"])->name('matriz.abonos');
Route::get ('/reportes/prestamos-activos', [ReportesController::class, "prestamosActivosGet"]);

//autenticacion
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);