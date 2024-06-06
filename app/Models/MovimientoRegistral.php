<?php

namespace App\Models;

use App\Models\Gravamen;
use App\Models\FolioReal;
use App\Models\Propiedad;
use App\Models\Cancelacion;
use App\Traits\ModelosTrait;
use App\Constantes\Constantes;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MovimientoRegistral extends Model implements Auditable
{

    use HasFactory;
    use HasFactory;
    use ModelosTrait;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'fecha_entrega' => 'date',
        'fecha_prelacion' => 'datetime',
        'fecha_pago' => 'datetime'
    ];

    public function getEstadoColorAttribute()
    {
        return [
            'nuevo' => 'blue-400',
            'concluido' => 'gray-400',
            'rechazado' => 'red-400',
            'elaborado' => 'green-400',
        ][$this->estado] ?? 'gray-400';
    }

    public function folioReal(){
        return $this->belongsTo(FolioReal::class, 'folio_real');
    }

    public function certificacion(){
        return $this->hasOne(Certificacion::class);
    }

    public function inscripcionPropiedad(){
        return $this->hasOne(Propiedad::class);
    }

    public function cancelacion(){
        return $this->hasOne(Cancelacion::class);
    }

    public function gravamen(){
        return $this->hasOne(Gravamen::class);
    }

    public function sentencia(){
        return $this->hasOne(Sentencia::class);
    }

    public function vario(){
        return $this->hasOne(Vario::class);
    }

    public function supervisor(){
        return $this->belongsTo(User::class, 'usuario_supervisor');
    }

    public function asignadoA(){
        return $this->belongsTo(User::class, 'usuario_asignado');
    }

    public function getDistritoAttribute(){
        return Constantes::DISTRITOS[$this->attributes['distrito']];
    }

}
