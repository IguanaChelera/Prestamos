<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Empleado;
use App\Models\Abono;

class Prestamo extends Model
{
    use HasFactory;
    protected $table = 'prestamo';
    protected $primaryKey = 'id_prestamo';
    
    protected $fillable = [
        'fk_id_empleado',
        'fecha_solicitud',
        'monto',
        'plazo',
        'fecha_aprob',
        'tasa_mensual', 
        'pago_fijo_cap',
        'fecha_ini_desc',
        'fecha_fin_desc',
        'saldo_actual',
        'estado'
    ];
    
    public function empleado(){
        return $this->belongsTo(Empleado::class, 'fk_id_empleado', 'id_empleado');
    }
    
    public function abonos(){
        return $this->hasMany(Abono::class, 'fk_id_prestamo', 'id_prestamo');
    }
}