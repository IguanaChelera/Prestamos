@extends("components.layout")
@section("content")
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent
<div class="row my-4">
    <div class="col">
        <h1>Reportes</h1>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <a href='{{ url("/reportes/prestamos-activos") }}' class="btn-menu">Préstamos vigentes</a>
        </div>
        <div class="col-md-4">
            <a href='{{ url("/reportes/prestamos-vencidos") }}' class="btn-menu">Resumen de abonos cobrados</a>
        </div>
    </div>
</div>
@endsection