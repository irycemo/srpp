<?php

namespace App\Models;

use App\Models\Actor;
use App\Models\Escritura;
use App\Models\FolioReal;
use App\Models\Colindancia;
use App\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Predio extends Model implements Auditable
{

    use HasFactory;
    use ModelosTrait;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = ['id', 'created_at', 'updated_At'];

    public function actores(){
        return $this->morphMany(Actor::class, 'actorable');
    }

    public function propietarios(){
        return $this->actores()->with('persona')->where('tipo_Actor', 'propietario')->get();
    }

    public function transmitentes(){
        return $this->actores()->with('persona')->where('tipo_Actor', 'transmitente')->get();
    }

    public function representantes(){
        return $this->actores()->with('persona', 'representados.persona')->where('tipo_Actor', 'representante')->get();
    }

    public function colindancias(){
        return $this->hasMany(Colindancia::class);
    }

    public function escritura(){
        return $this->belongsTo(Escritura::class, 'escritura_id');
    }

    public function folioReal(){
        return $this->belongsTo(FolioReal::class, 'folio_real');
    }

    public function primerPropietario(){

        if($this->propietarios()->first())
            return $this->propietarios()->first()->persona->nombre . ' ' . $this->propietarios()->first()->persona->ap_paterno . ' ' . $this->propietarios()->first()->persona->ap_materno;
        else
            return null;
    }

    public function cuentaPredial(){

        return $this->cp_localidad . '-' . $this->cp_oficina . '-' . $this->cp_tipo_predio . '-' . $this->cp_registro;

    }

    public function claveCatastral(){

        return $this->cc_estado . '-' . $this->cc_region_catastral . '-' . $this->cc_municipio . '-' . $this->cc_zona_catastral . '-' . $this->cp_localidad . '-' . $this->cc_sector . '-' . $this->cc_manzana . '-' . $this->cc_predio . '-' . $this->cc_edificio . '-' . $this->cc_departamento;

    }

}
