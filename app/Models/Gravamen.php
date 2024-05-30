<?php

namespace App\Models;

use App\Models\Deudor;
use App\Models\Acreedor;
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

    public function acreedores(){
        return $this->hasMany(Acreedor::class);
    }

    public function deudores(){
        return $this->hasMany(Deudor::class);
    }

    public function deudoresUnicos(){
        return $this->hasMany(Deudor::class)->with('actor.persona', 'persona')->where('tipo', 'I-DEUDOR ÚNICO');
    }

    public function garantesHipotecarios(){
        return $this->hasMany(Deudor::class)->with('actor.persona', 'persona')->where('tipo', 'D-GARANTE(S) HIPOTECARIO(S)');
    }

    public function parteAlicuota(){
        return $this->hasMany(Deudor::class)->with('actor.persona', 'persona')->where('tipo', 'P-PARTE ALICUOTA');
    }

    public function garantesCoopropiedad(){
        return $this->hasMany(Deudor::class)->with('actor.persona', 'persona')->where('tipo', 'G-GARANTES EN COOPROPIEDAD');
    }

    public function fianza(){
        return $this->hasMany(Deudor::class)->with('actor.persona', 'persona')->where('tipo', 'F-FIANZA');
    }

}
