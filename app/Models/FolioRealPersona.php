<?php

namespace App\Models;

use App\Models\File;
use App\Models\Actor;
use App\Models\Escritura;
use App\Traits\ModelosTrait;
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

    public function actores(){
        return $this->morphMany(Actor::class, 'actorable');
    }

    public function movimientosRegistrales(){
        return $this->hasMany(MovimientoRegistral::class, 'folio_real_persona');
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

}
