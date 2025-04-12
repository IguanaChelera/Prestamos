<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View; 
use App\Models\Empleado;
use App\Models\Prestamo;
use App\Models\Abono;
use App\Models\Puesto;
use Carbon\Carbon;
use DateTime;

class MovimientosController extends Controller 
{
    public function prestamosGet(): View{
        $prestamos = Prestamo::join("empleado", "prestamo.fk_id_empleado", "=", "empleado.id_empleado") -> get();
        return view ('movimientos/prestamosGet', 
        [
            'prestamos' => $prestamos,
            "breadcrumbs" => 
            ["Inicio" => url("/"),
            "Prestamos" => url("/movimientos/prestamos")]
        ]);
    }

    public function prestamosAgregarGet(): View 
    {
        // 1. Fechas más legibles (usando Carbon consistentemente)
        $haceUnAno = now()->subYear()->format('Y-m-d');
        $fechaActual = now();
        
        // 2. Consultas más eficientes (eliminando ->all() innecesario)
        $empleados = Empleado::where('fecha_ingreso', '<', $haceUnAno)
            ->get()
            ->keyBy('id_empleado');
        
        // 3. Prestamos vigentes con carga eager del empleado (si es necesario)
        $prestamosVigentes = Prestamo::with('empleado')
            ->where('fecha_ini_desc', '<=', $fechaActual)
            ->where('fecha_fin_desc', '>=', $fechaActual)
            ->get()
            ->keyBy('fk_id_empleado');
        
        // 4. Filtrado más seguro
        $empleadosDisponibles = $empleados->diffKeys($prestamosVigentes);
        
        // 5. Validación de datos vacíos
        if ($empleadosDisponibles->isEmpty()) {
            return view('movimientos.prestamosAgregarGet', [
                "empleados" => collect(),
                "breadcrumbs" => [
                    "Inicio" => url("/"),
                    "Prestamos" => url("/movimientos/prestamos"),
                    "Agregar" => url("/movimientos/prestamos/agregar")
                ],
                "warning" => 'No hay empleados disponibles para nuevos préstamos'
            ]);
        }
    
        return view('movimientos.prestamosAgregarGet', [
            "empleados" => $empleadosDisponibles,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Prestamos" => url("/movimientos/prestamos"),
                "Agregar" => url("/movimientos/prestamos/agregar")
            ]
        ]);
    }
    public function prestamosAgregarPost(Request $request)
    {
        // Validación de datos
        $request->validate([
            'id_empleado' => 'required|exists:empleado,id_empleado',
            'monto' => 'required|numeric|min:1',
            // Agrega más validaciones según necesites
        ]);
    
        $fk_id_empleado = $request->input("id_empleado");
        $monto = $request->input("monto");
    
        // Verificación de límite de préstamo
        $puesto = Puesto::join("Det_emp_puesto", "puesto.id_puesto", "=", "Det_emp_puesto.fk_id_puesto")
            ->where("Det_emp_puesto.fk_id_empleado", "=", $fk_id_empleado)
            ->whereNull("Det_emp_puesto.fecha_fin")
            ->firstOrFail();
    
        $sueldox6 = $puesto->sueldo * 6;
        if ($monto > $sueldox6) {
            return back()->with('error', 'La solicitud excede el monto permitido');
        }
    
        // Creación del préstamo
        $prestamo = new Prestamo([
            "fk_id_empleado" => $fk_id_empleado,
            "fecha_solicitud" => $request->input("fecha_solicitud"),
            "monto" => $monto,
            "plazo" => $request->input("plazo"),
            "fecha_aprob" => $request->input("fecha_aprob"),
            "tasa_mensual" => $request->input("tasa_mensual"),
            "pago_fijo_cap" => $request->input("pago_fijo_cap"),
            "fecha_ini_desc" => $request->input("fecha_ini_desc"),
            "fecha_fin_desc" => $request->input("fecha_fin_desc"),
            'saldo_actual' => $monto,  // Usamos la variable $monto directamente
            'estado' => 0  // Estado fijo como 0 (activo) al crear
        ]);
    
        $prestamo->save();
    
        return redirect("/movimientos/prestamos")->with('success', 'Préstamo creado exitosamente');
    }
    

    
    public function abonosGet($id_prestamo): View 
    {
        $abonos = Abono::where("fk_id_prestamo", $id_prestamo)->get()->all();
        $prestamo = Prestamo::join("empleado", "empleado.id_empleado", "=", "prestamo.fk_id_empleado")
            ->where("id_prestamo", $id_prestamo)->first();
        return view('movimientos/abonosGet', [
            'abonos' => $abonos,
            'prestamo' => $prestamo,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Prestamos" => url("/movimientos/prestamos"),
                "Abonos" => url ("/movimientos/prestamos/abonos"),
            ]
            ]);
    }


    public function abonosAgregarGet($id_prestamo): View
    {
        $prestamo = Prestamo::join ("empleado", "empleado.id_empleado", "prestamo.fk_id_empleado")
            ->where("id_prestamo", $id_prestamo)->first();
        $abonos = Abono::where("abono.fk_id_prestamo", $id_prestamo)->get();
        $num_abono = count($abonos)+1;
        $pago_fijo_cap = $prestamo->pago_fijo_cap;
        $monto_interes = $prestamo->saldo_actual*$prestamo->tasa_mensual/100;
        $monto_cobrado = $prestamo->pago_fijo_cap+$monto_interes;
        $saldo_pendiente = $prestamo->saldo_actual-$prestamo->pago_fijo_cap;
        if ($saldo_pendiente < 0.01) {
            $pago_fijo_cap -= $saldo_pendiente;
            $saldo_pendiente = 0;
        }
    
    return view ('movimientos/abonosAgregarGet', [
        'prestamo' => $prestamo,
        "num_abono" => $num_abono,
        "pago_fijo_cap" => $pago_fijo_cap,
        "monto_interes" => $monto_interes,
        "monto_cobrado" => $monto_cobrado,
        "saldo_pendiente" => $saldo_pendiente,
        "breadcrumbs" => [
            "Inicio" => url("/"),
            "Prestamos" => url("/movimientos/prestamos"),
            "Abonos" => url ("/prestamos/{$prestamo->id_prestamo}/abonos"),
            "Agregar" => "",
        ]
        ]);
    }
    
    public function abonosAgregarPost(Request $request)
    {
        $fk_id_prestamo = $request->input("fk_id_prestamo");
        $num_abono = $request->input("num_abono");
        $fecha = $request->input("fecha");
        $monto_capital = $request->input("monto_capital");
        $monto_interes = $request->input("monto_interes");
        $monto_cobrado = $request->input("monto_cobrado");
        $saldo_pendiente = $request->input("saldo_pendiente");
        $abono = new Abono([
            "fk_id_prestamo" => $fk_id_prestamo,
            "num_abono" => $num_abono,
            "fecha" => $fecha,
            "monto_capital" => $monto_capital,
            "monto_interes" => $monto_interes,
            "monto_cobrado" => $monto_cobrado,
            "saldo_pendiente" => $saldo_pendiente
        ]);
        $abono->save();
        $prestamo=Prestamo::find($fk_id_prestamo);
        $prestamo->saldo_actual=$saldo_pendiente;
        if ($saldo_pendiente < 0.01) {
            $prestamo->estado = 1;
        }
        $prestamo->save();
        return redirect("/prestamos/{$fk_id_prestamo}/abonos");
    }

    public function empleadosPrestamosGet(Request $request, $id_empleado): View
    {
        $empleado = Empleado::find($id_empleado);

        $prestamos = Prestamo::where("prestamo.fk_id_empleado", $id_empleado)->get();
        return view('movimientos/empleadosPrestamosGet', [
            "empleado" => $empleado,
            "prestamos" => $prestamos,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Prestamos" => url("/movimientos/prestamos")
            ]
        ]);
    }
}
