<?php

namespace App\Models;

use App\Models\Actor;
use App\Traits\ModelosTrait;
use App\Models\MovimientoRegistral;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gravamen extends Model implements Auditable
{
    use HasFactory;
    use ModelosTrait;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function movimientoRegistral(){
        return $this->belongsTo(MovimientoRegistral::class);
    }

    public function actores(){
        return $this->morphMany(Actor::class, 'actorable');
    }

    public function acreedores(){
        return $this->actores()->with('persona')->where('tipo_actor', 'acreedor');
    }

    public function deudores(){
        return $this->actores()->with('persona')->where('tipo_actor', 'deudor');
    }

    public function deudoresUnicos(){
        return $this->actores()->with('persona')->where('tipo_deudor', 'I-DEUDOR ÚNICO');
    }

    public function garantesHipotecarios(){
        return $this->actores()->with('persona')->where('tipo_deudor', 'D-GARANTE(S) HIPOTECARIO(S)');
    }

    public function parteAlicuota(){
        return $this->actores()->with('persona')->where('tipo_deudor', 'P-PARTE ALICUOTA');
    }

    public function garantesCoopropiedad(){
        return $this->actores()->with('persona')->where('tipo_deudor', 'G-GARANTES EN COOPROPIEDAD');
    }

    public function fianza(){
        return $this->actores()->with('persona')->where('tipo_deudor', 'F-FIANZA');
    }

}
