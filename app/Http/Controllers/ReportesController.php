<?php

namespace App\Http\Controllers;

use App\Models\Abono;
use App\Models\Prestamo;
use Illuminate\Support\Facades\DB;
use DateTime;
use Franczak\PowerData\Index;
use Illuminate\Http\Request;
use Carbon\Carbon;



class ReportesController extends Controller
{
    public function indexGet (Request $request) {
        return view ('reportes.indexGet', 
        ["breadcrumbs" => [
            "Inicio" => url("/"), 
            "Reportes" => url("/reportes/prestamos-activos")
            ]
        ]);
    }        

    public function prestamosActivosGet(Request $request)
    {
    $fecha = Carbon::now()->format("Y-m-d"); 
    $fecha = $request->query("fecha", $fecha);
    $prestamos = Prestamo::join("empleado", "empleado.id_empleado", "=", "prestamo.fk_id_empleado")
        ->leftJoin("abono", "abono.fk_id_prestamo", "=", "prestamo.id_prestamo")
        ->select("prestamo.id_prestamo", "empleado.nombre", "prestamo.monto")
        ->selectRaw("SUM(abono.monto_capital) AS total_capital")
        ->selectRaw("SUM(abono.monto_interes) AS total_interes")
        ->selectRaw("SUM(abono.monto_cobrado) AS total_cobrado")
        ->groupBy("prestamo.id_prestamo", "empleado.nombre", "prestamo.monto")
        ->where("prestamo.fecha_ini_desc", "<=", $fecha)
        ->where("prestamo.fecha_fin_desc", ">=", $fecha)
        ->get()->all();
    // var_dump($prestamos);
    return view("/reportes/prestamosActivosGet", [
        "fecha" => $fecha,
        "prestamos" => $prestamos,
        "breadcrumbs" => [
            "Inicio" => url("/"),
            "Reportes" => url("/reportes/prestamos-activos")
        ]
    ]);
    }

    public function matrizAbonosGet(Request $request)
    {
        $fecha_inicio = $request->query("fecha_inicio", Carbon::now()->format("Y-01-01"));
        $fecha_fin = $request->query("fecha_fin", Carbon::now()->format("Y-12-31"));
        
        $abonos = Abono::join("prestamo", "prestamo.id_prestamo", "=", "abono.fk_id_prestamo")
            ->join("empleado", "empleado.id_empleado", "=", "prestamo.fk_id_empleado")
            ->select(
                "prestamo.id_prestamo",
                "empleado.nombre",
                DB::raw("DATE_FORMAT(abono.fecha, '%Y-%m') AS fecha"),
                DB::raw("(abono.monto_capital + abono.monto_interes) AS monto_cobrado")
            )
            
            ->whereBetween("abono.fecha", [$fecha_inicio, $fecha_fin])
            ->orderBy("abono.fecha")
            ->get()
            ->groupBy(['id_prestamo', 'fecha']);
    
        // Obtener todas las fechas únicas para garantizar consistencia
        $fechasUnicas = collect($abonos->collapse()->keys())->unique()->sort()->values();
    
        return view("reportes.matrizAbonosGet", [
            "abonos" => $abonos,
            "fechas" => $fechasUnicas, // Pasamos las fechas ya procesadas
            "fecha_inicio" => $fecha_inicio,
            "fecha_fin" => $fecha_fin,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Reportes" => url("/reportes"),
                "Matriz de Abonos" => ""
            ]
        ]);
    }
}
