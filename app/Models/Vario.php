<?php

namespace App\Models;

use App\Models\Predio;
use App\Traits\ModelosTrait;
use App\Models\MovimientoRegistral;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vario extends Model implements Auditable
{

    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use ModelosTrait;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function movimientoRegistral(){
        return $this->belongsTo(MovimientoRegistral::class);
    }

    public function actores(){
        return $this->morphMany(Actor::class, 'actorable');
    }

    public function propietarios(){
        return $this->actores()->with('persona')->where('tipo_Actor', 'propietario')->get();
    }

    public function predio(){
        return $this->belongsTo(Predio::class, 'predio_id');
    }

    public function fiduciarias(){
        return $this->actores()->with('persona')->where('tipo_Actor', 'FIDUCIARIA')->get();
    }

    public function fideicomitentes(){
        return $this->actores()->with('persona')->where('tipo_Actor', 'FIDECOMITENTE')->get();
    }

    public function fideicomisarios(){
        return $this->actores()->with('persona')->where('tipo_Actor', 'FIDEICOMISARIO')->get();
    }

}
