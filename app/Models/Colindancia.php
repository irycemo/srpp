<?php

namespace App\Models;

use App\Models\Predio;
use App\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Colindancia extends Model
{
    use HasFactory;
    use ModelosTrait;

    protected $fillable = ['predio_id', 'viento', 'longitud', 'descripcion', 'creado_por', 'actualizado_por'];

    public function predio(){
        return $this->belongsTo(Predio::class);
    }

    public function getLongitudFormateadaAttribute(){

        return $this->formatear($this->attributes['longitud']);

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
