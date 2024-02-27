<?php

namespace App\Models;

use App\Models\Actor;
use App\Traits\ModelosTrait;
use App\Models\MovimientoRegistral;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Propiedad extends Model implements Auditable
{

    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use ModelosTrait;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function actores(){
        return $this->morphMany(Actor::class, 'actorable');
    }

    public function propietarios(){
        return $this->actores()->with('persona', 'representadoPor.persona')->where('tipo_Actor', 'propietario')->get();
    }

    public function transmitentes(){
        return $this->actores()->with('persona', 'representadoPor.persona')->where('tipo_Actor', 'transmitente')->get();
    }

    public function representantes(){
        return $this->actores()->with('persona', 'representados.persona')->where('tipo_Actor', 'representante')->get();
    }

    public function movimientoRegistral(){
        return $this->belongsTo(MovimientoRegistral::class);
    }

}
