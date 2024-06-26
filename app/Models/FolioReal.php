<?php

namespace App\Models;

use App\Models\Vario;
use App\Models\Predio;
use App\Models\Gravamen;
use App\Models\Sentencia;
use App\Models\Antecedente;
use App\Traits\ModelosTrait;
use App\Constantes\Constantes;
use App\Models\MovimientoRegistral;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FolioReal extends Model implements Auditable
{

    use HasFactory;
    use ModelosTrait;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getEstadoColorAttribute()
    {
        return [
            'nuevo' => 'blue-400',
            'captura' => 'yellow-400',
            'rechazado' => 'red-400',
            'activo' => 'green-400',
            'bloqueado' => 'black',
        ][$this->estado] ?? 'gray-400';
    }

    public function movimientosRegistrales(){
        return $this->hasMany(MovimientoRegistral::class, 'folio_real');
    }

    public function gravamenes(){
        return $this->hasManyThrough(Gravamen::class, MovimientoRegistral::class, 'folio_real', 'movimiento_registral_id', 'id', 'id');
    }

    public function sentencias(){
        return $this->hasManyThrough(Sentencia::class, MovimientoRegistral::class, 'folio_real', 'movimiento_registral_id', 'id', 'id');
    }

    public function varios(){
        return $this->hasManyThrough(Vario::class, MovimientoRegistral::class, 'folio_real', 'movimiento_registral_id', 'id', 'id');
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

    public function folioRealAntecedente(){
        return $this->belongsTo(FolioReal::class, 'antecedente');
    }

    public function antecedentes(){
        return $this->hasMany(Antecedente::class, 'folio_real');
    }

}
