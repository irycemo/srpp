<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\File;
use App\Models\Vario;
use App\Models\Predio;
use App\Models\Gravamen;
use App\Models\Propiedad;
use App\Models\Sentencia;
use App\Models\Antecedente;
use App\Models\Cancelacion;
use App\Traits\ModelosTrait;
use App\Models\Certificacion;
use App\Constantes\Constantes;
use App\Models\MovimientoRegistral;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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
            'elaborado' => 'green-400',
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

    public function cancelaciones(){
        return $this->hasManyThrough(Cancelacion::class, MovimientoRegistral::class, 'folio_real', 'movimiento_registral_id', 'id', 'id');
    }

    public function varios(){
        return $this->hasManyThrough(Vario::class, MovimientoRegistral::class, 'folio_real', 'movimiento_registral_id', 'id', 'id');
    }

    public function propiedad(){
        return $this->hasManyThrough(Propiedad::class, MovimientoRegistral::class, 'folio_real', 'movimiento_registral_id', 'id', 'id');
    }

    public function certificaciones(){
        return $this->hasManyThrough(Certificacion::class, MovimientoRegistral::class, 'folio_real', 'movimiento_registral_id', 'id', 'id');
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

    public function archivos(){
        return $this->morphMany(File::class, 'fileable');
    }

   /*  public function caratula(){
        return $this->morphMany(File::class, 'fileable')->where('descripcion', 'caratula');
    } */

    public function caratula(){

        return $this->archivos()->where('descripcion', 'caratula')->first()
                ? Storage::disk('caratulas')->url($this->archivos()->where('descripcion', 'caratula')->first()->url)
                : null;
    }

    public function documentoEntrada(){
        return $this->archivos()->where('descripcion', 'documento_entrada')->latest()->first()
                ? Storage::disk('documento_entrada')->url($this->archivos()->where('descripcion', 'documento_entrada')->latest()->first()->url)
                : null;
    }

    public function avisoPreventivo(){

        $movimiento = $this->movimientosRegistrales()
                                        ->whereHas('vario', function($q){
                                            $q->whereIn('servicio', ['DL09', 'D110'])
                                                ->where('estado', 'activo');
                                        })
                                        ->whereIn('estado', ['concluido', 'elaborado'])
                                        ->orderBy('fecha_inscripcion', 'desc')
                                        ->first();

        if($movimiento) {

            if($movimiento->vario->acto_contenido == "SEGUNDO AVISO PREVENTIVO"){


                if(now() > Carbon::parse($movimiento->vario->fecha_inscripcion)->addDays(60)){

                    return null;

                }else{

                    return $movimiento->vario;

                }

            }else if($movimiento->vario->acto_contenido == "PRIMER AVISO PREVENTIVO"){

                if(now() > Carbon::parse($movimiento->vario->fecha_inscripcion)->addDays(30)){

                    return null;

                }else{

                    return $movimiento->vario;

                }

            }

        }else{

            return null;
        }

    }

    public function aclaracionAdministrativa(){

        $movimiento = $this->movimientosRegistrales()
                                        ->whereHas('vario', function($q){
                                            $q->whereIn('servicio', ['D112']);
                                        })
                                        ->where('estado','!=', 'concluido')
                                        ->first();

        return $movimiento;

    }

}
