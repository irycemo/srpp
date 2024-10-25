<?php

namespace App\Models;

use App\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Actor extends Model implements Auditable
{

    use HasFactory;
    use ModelosTrait;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function actorable(){
        return $this->morphTo();
    }

    public function persona(){
        return $this->belongsTo(Persona::class);
    }

    public function representados(){
        return $this->hasMany(Actor::class, 'representado_por');
    }

    public function representadoPor(){
        return $this->belongsTo(Actor::class,'representado_por');
    }

    public function getPorcentajePropiedadFormateadaAttribute(){

        if($this->attributes['porcentaje_propiedad'] == 0) return 0;

        return $this->formatear($this->attributes['porcentaje_propiedad']);

    }

    public function getPorcentajeNudaFormateadaAttribute(){

        if($this->attributes['porcentaje_nuda'] == 0) return 0;

        return $this->formatear($this->attributes['porcentaje_nuda']);

    }

    public function getPorcentajeUsufructoFormateadaAttribute(){

        if($this->attributes['porcentaje_usufructo'] == 0) return 0;

        return $this->formatear($this->attributes['porcentaje_usufructo']);

    }

    public function formatear($numero){

        $numero = $numero + 0.0;

        $partes = explode('.', strval($numero));

        if(!isset($partes[1])){

            return $partes[0] . '.00';

        }

        $lenDecimal = strlen($partes[1]);

        if($lenDecimal == 1){

            return $partes[0] . '.' . $partes[1] . '0';

        }

        return $partes[0] . '.' . $partes[1];

    }

}
