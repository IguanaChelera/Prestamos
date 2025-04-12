@extends ("components.layout")
@section("content")
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent
<h1>Abonos del Prestamo {{$prestamo->id_prestamo}}</h1>

<div class = "card">
    <div class = "row card-body">
        <div class = "col-2">Empleado</div>
        <div class = "col">{{$prestamo->nombre}}</div>
    </div>

    <div class = "row card-body">
        <div class = "col-2">Id Prestamo</div>
        <div class = "col-2"> {{$prestamo->id_prestamo}}</div>
        <div class = "col-2">Fecha Aprobacion</div>
        <div class = "col-2">{{$prestamo->fecha_aprob}}</div>
        <div class = "col-2">Monto Prestado</div>
        <div class = "col-2">{{$prestamo->monto}}</div>
    </div>
</div>

<div class = "row my-3">
    <div class = "col">
        <h2>Abonos</h2>
    </div>
    <div class = "col-auto">
        <a class = "btn btn-primary" href = '{{url("/prestamos/{$prestamo->id_prestamo}/abonos/agregar")}}'>Agregar</a>
    </div>
</div>
<table class="table" id="maintable">
<thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">NUM DE ABONO</th>
        <th scope="col">FECHA</th>
        <th scope="col">MONTO CAPITAL</th>
        <th scope="col">MONTO INTERES</th>
        <th scope="col">MONTO COBRADO</th>
        <th scope="col">SALDO PENDIENTE</th>
    </tr>
</thead>
<tbody>
@foreach ($abonos as $abono)
    <tr>
        <td>{{$abono->id_abono}}</td>
        <td class="text-center">{{$abono->num_abono}}</td>
        <td>{{$abono->fecha}}</td>
        <td class="text-end">{{number_format($abono->monto_capital, 2)}}</td>
        <td class="text-end">{{number_format($abono->monto_interes, 2)}}</td>
        <td class="text-end">{{number_format($abono->monto_cobrado, 2)}}</td>
        <td class="text-end">{{number_format($abono->saldo_actual ?? $abono->saldo_pendiente, 2)}}</td>
    </tr>
@endforeach
</tbody>
<tfoot>
    <tr>
        <td colspan="3" class="text-end fw-bold">Total</td>
        <td class="text-end">{{number_format($abonos->sum('monto_capital'), 2)}}</td>
        <td class="text-end">{{number_format($abonos->sum('monto_interes'), 2)}}</td>
        <td class="text-end">{{number_format($abonos->sum('monto_cobrado'), 2)}}</td>
        <td></td>
    </tr>
</tfoot>
</table>

<script>
    let table = document.getElementById("maintable")
    let datatable = new DataTable(table,{paging:true, searching:true})
</script>
@endsection