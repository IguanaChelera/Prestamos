@extends("components.layout")
@section("content")
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent
<div class = "row">
    <div class = "form-group my-3">
        <h1>Agregar puesto</h1>
    </div>
    <div class = "col"></div>
</div>

<form method = "post" action = "{{url('/catalogos/puestos/agregar')}}">
    @csrf
    <div class = "row my-2">
        <div class = "form-group">
            <label for = "nombre">Nombre:</label> </br>
            <input type = "text" maxlength = "50" name = "nombre" id = "nombre" placeholder = "Ingrese el nombre del puesto"
             class = "from-control" required autofocus>
        </div>
    </div>
    <div class = "form-group">
        <label for = "sueldo">Sueldo:</label>
        <input type = "number" name = "sueldo" id = "sueldo" class = "form-control" required>
    </div>
    <div>
        <div class = "col"></div>
        <div class = "col-auto">
            <br>
            <button type = "submit" class = "btn btn-primary">Guardar</button>
        </div>
    </div>
</form>
@endsection