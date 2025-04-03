@extends("components.layout")
@section("content")
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent
<div class="container">
    <h2>Resumen de Abonos Cobrados</h2>

    <!-- Formulario de filtrado -->
    <form class="card p-4 my-4" action="{{ url('/reportes/resumen-abonos') }}" method="get">
        <div class="row">
            <div class="col form-group">
                <label for="txtfecha">Fecha</label>
                <input class="form-control" type="date" name="fecha" id="txtfecha" value="{{ $fecha }}">
            </div>
            <div class="col-auto">
                <br>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Mostrar el total de abonos cobrados -->
    <p><strong>Total Abonos Cobrados:</strong> ${{ number_format($totalAbonos, 2) }}</p>

    <!-- Tabla de abonos por fecha -->
    <h3>Abonos por Fecha</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Total Abonado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($abonosPorFecha as $abono)
                <tr>
                    <td>{{ $abono->fecha }}</td>
                    <td>${{ number_format($abono->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection