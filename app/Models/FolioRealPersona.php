<?php

namespace App\Models;

use App\Models\File;
use App\Models\Actor;
use App\Models\Escritura;
use App\Models\ReformaMoral;
use App\Traits\ModelosTrait;
use App\Constantes\Constantes;
use App\Models\ObjetoPersonaMOral;
use App\Models\MovimientoRegistral;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FolioRealPersona extends Model implements Auditable
{

    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use ModelosTrait;

    protected $guarded = ['id', 'created_at', 'updated_At'];

    public function getEstadoColorAttribute()
    {
        return [
            'nuevo' => 'blue-400',
            'captura' => 'yellow-400',
            'rechazado' => 'red-400',
            'activo' => 'green-400',
            'bloqueado' => 'red-400',
            'centinela' => 'red-400',
            'elaborado' => 'green-400',
            'matriz' => 'indigo-400'
        ][$this->estado] ?? 'gray-400';
    }

    public function actores(){
        return $this->morphMany(Actor::class, 'actorable');
    }

    public function movimientosRegistrales(){
        return $this->hasMany(MovimientoRegistral::class, 'folio_real_persona');
    }

    public function reformas(){
        return $this->hasManyThrough(ReformaMoral::class, MovimientoRegistral::class, 'folio_real_persona', 'movimiento_registral_id', 'id', 'id');
    }

    public function escritura(){
        return $this->belongsTo(Escritura::class);
    }

    public function archivos(){
        return $this->morphMany(File::class, 'fileable');
    }

    public function documentoEntrada(){
        return $this->archivos()->where('descripcion', 'documento_entrada')->latest()->first()
                ? Storage::disk('documento_entrada')->url($this->archivos()->where('descripcion', 'documento_entrada')->first()->url)
                : null;
    }

    public function objetos(){
        return $this->hasMany(ObjetoPersonaMOral::class, 'folio_real_persona');
    }

    public function objetoActual(){
        return $this->hasOne(ObjetoPersonaMOral::class, 'folio_real_persona')->where('estado', 'activo');
    }

    public function getDistritoAttribute(){
        return Constantes::DISTRITOS[$this->attributes['distrito']];
    }

    public function ultimoFolio():int
    {

        $movimiento = MovimientoRegistral::where('folio_real_persona', $this->id)->orderBy('folio', 'desc')->first();

        if($movimiento)
            return $movimiento->folio;
        else
            return 0;

    }

}
