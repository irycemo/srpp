<?php

namespace App\Models;

use App\Models\Predio;
use App\Traits\ModelosTrait;
use App\Constantes\Constantes;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FolioReal extends Model implements Auditable
{

    use HasFactory;
    use ModelosTrait;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function movimientosRegistrales(){
        return $this->hasMany(MovimientoRegistral::class);
    }

    public function predio(){
        return $this->hasOne(Predio::class, 'folio_real');
    }

    public function getDistritoAttribute(){
        return Constantes::DISTRITOS[$this->attributes['distrito_antecedente']];
    }

    public function ultimoFolio():int
    {

        $folio = MovimientoRegistral::where('folio_real', $this->id)->orderBy('folio', 'desc')->first()->folio;

        if($folio)
            return $folio;
        else
            return 0;

    }

}
