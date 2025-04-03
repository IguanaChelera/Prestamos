@if(Auth::check())
<nav class="sidebar nav flex-column pt-5">
    <a href="{{url('/catalogos/puestos')}}" class="nav-link">Puestos</a>
    <a href="{{url('/catalogos/empleados')}}" class="nav-link">Empleados</a>
    <a href="{{url('/movimientos/prestamos')}}" class="nav-link">Préstamos</a>
    <a href="{{url('/reportes/')}}" class="nav-link">Reportes</a>
    <a href="{{url('/logout')}}" class="nav-link">Salir</a>
    <a href = "#" onclick = "event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">Logout</a>
</nav>
@endif