<?php

namespace App\Models;

use App\Models\Rancho;
use App\Models\Tenencia;
use App\Models\Municipio;
use App\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Distrito extends Model implements Auditable
{

    use HasFactory;
    use ModelosTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['clave', 'nombre', 'creado_por', 'actualizado_por'];

    public function municipios(){
        return $this->hasMany(Municipio::class);
    }

    public function tenencias(){
        return $this->hasMany(Tenencia::class);
    }

    public function ranchos(){
        return $this->hasMany(Rancho::class);
    }

}
