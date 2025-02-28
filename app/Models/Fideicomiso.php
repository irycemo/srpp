<?php

namespace App\Models;

use App\Traits\ModelosTrait;
use App\Models\MovimientoRegistral;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fideicomiso extends Model implements Auditable
{

    use HasFactory;
    use ModelosTrait;
    use \OwenIt\Auditing\Auditable;

    public $guarded = ['id', 'created_at', 'updated_at'];

    public function actores(){
        return $this->morphMany(Actor::class, 'actorable');
    }

    public function fiduciarias(){
        return $this->actores()->with('persona')->where('tipo_Actor', 'FIDUCIARIA')->get();
    }

    public function fideicomitentes(){
        return $this->actores()->with('persona')->where('tipo_Actor', 'FIDEICOMITENTE')->get();
    }

    public function fideicomisarios(){
        return $this->actores()->with('persona')->where('tipo_Actor', 'FIDEICOMISARIO')->get();
    }

    public function movimientoRegistral(){
        return $this->belongsTo(MovimientoRegistral::class);
    }

}
