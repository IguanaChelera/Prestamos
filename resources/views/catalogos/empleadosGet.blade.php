@extends("components.layout")
@section("content")
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent
<div class = "row my-4">
    <div class = "col">
        <h1>Empleados</h1>
    </div>
    <div class = "col-auto titlebar-commands">
        <a class = "btn btn-primary" href= "{{url('/catalogos/empleados/agregar')}}">Agregar</a>
    </div>
</div>

<table class = "table" id = "maintable">
<thead>
    <tr>
        <th scope = "col">ID</th>
        <th scope = "col">NOMBRE</th>
        <th scope = "col">FECHA DE INGRESO</th>
        <th scope = "col">ACTIVO</th>
        <th scpoe = "col">ACCIONES</th>
    </tr>
</thead>
<tbody>
@foreach($empleados as $empleado)
    <tr>
        <td class = "text-center">{{$empleado->id_empleado}}</td>
        <td class = "text-center">{{$empleado->nombre}}</td>
        <td class = "text-center">{{$empleado->fecha_ingreso}}</td>
        <td class = "text-center">{{$empleado->activo}}</td>
        <td class = "text-center"> 
            <a href = "{{url('/empleados/'.$empleado->id_empleado.'/puestos')}}" class = "btn btn-primary">Puestos</a>
            <a href  = "{{url('/empleados/'.$empleado->id_empleado.'/prestamos')}}" class = "btn btn-primary">Prestamos</a>
            <a href = "{{url('/empleados/'.$empleado -> id_empleado.'/puestos/cambiar')}}" class = "btn btn-primary">Modificar</a>
        </td>
    </tr>
@endforeach
</tbody> 
</table>
@endsection