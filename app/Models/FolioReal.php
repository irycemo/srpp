<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\File;
use App\Models\Vario;
use App\Models\Predio;
use App\Models\Bloqueo;
use App\Models\Gravamen;
use App\Models\Propiedad;
use App\Models\Sentencia;
use App\Models\Antecedente;
use App\Models\Cancelacion;
use App\Models\Fideicomiso;
use Illuminate\Support\Str;
use App\Traits\ModelosTrait;
use App\Models\Certificacion;
use App\Constantes\Constantes;
use App\Models\FirmaElectronica;
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
            'bloqueado' => 'red-400',
            'centinela' => 'red-400',
            'elaborado' => 'green-400',
            'matriz' => 'indigo-400',
            'pendiente' => 'pink-400'
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

    public function fideicomisos(){
        return $this->hasManyThrough(Fideicomiso::class, MovimientoRegistral::class, 'folio_real', 'movimiento_registral_id', 'id', 'id');
    }

    public function certificaciones(){
        return $this->hasManyThrough(Certificacion::class, MovimientoRegistral::class, 'folio_real', 'movimiento_registral_id', 'id', 'id')->where('folio', '>=', 1);
    }

    public function predio(){
        return $this->hasOne(Predio::class, 'folio_real');
    }

    public function firmasElectronicas(){
        return $this->hasMany(FirmaElectronica::class, 'folio_real');
    }

    public function firmaElectronica(){
        return $this->hasOne(FirmaElectronica::class, 'folio_real')->where('estado', 'activo');
    }

    public function bloqueos(){
        return $this->hasMany(Bloqueo::class);
    }

    public function getDistritoAttribute(){
        return Constantes::DISTRITOS[$this->attributes['distrito_antecedente']];
    }

    public function ultimoFolio():int
    {

        $movimiento = MovimientoRegistral::where('folio_real', $this->id)->orderBy('folio', 'desc')->first();

        if($movimiento)
            return $movimiento->folio;
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

    public function caratula(){

        if(app()->isProduction()){

            if($this->archivos()->where('descripcion', 'caratula')->latest()->first()){

                $url = $this->archivos()->where('descripcion', 'caratula')->latest()->first()->url;

                if(Str::contains($this->url, config('services.ses.ruta_documento_entrada'))){

                    return Storage::disk('s3')->temporaryUrl($url, now()->addMinutes(10));

                }else{

                    return Storage::disk('s3')->temporaryUrl(config('services.ses.ruta_documento_entrada') . '/' . $url, now()->addMinutes(10));

                }

            }

        }else{

            return $this->archivos()->where('descripcion', 'caratula')->first()
                    ? Storage::disk('caratulas')->url($this->archivos()->where('descripcion', 'caratula')->first()->url)
                    : null;

        }

    }

    public function documentoEntrada(){

        if(app()->isProduction()){

            if($this->archivos()->where('descripcion', 'documento_entrada')->latest()->first()){

                $url = $this->archivos()->where('descripcion', 'documento_entrada')->latest()->first()->url;

                if(Str::contains($url, config('services.ses.ruta_documento_entrada'))){

                    return Storage::disk('s3')->temporaryUrl($url, now()->addMinutes(10));

                }else{

                    return Storage::disk('s3')->temporaryUrl(config('services.ses.ruta_documento_entrada') . '/' . $url, now()->addMinutes(10));

                }

            }else{

                return null;

            }

        }else{

            return $this->archivos()->where('descripcion', 'documento_entrada')->latest()->first()
                    ? Storage::disk('documento_entrada')->url($this->archivos()->where('descripcion', 'documento_entrada')->first()->url)
                    : null;

        }

    }

    public function variosActivos(){

        return $this->movimientosRegistrales()
                        ->withWhereHas('vario', function($q){
                            $q->where('estado', 'activo');
                        })
                        ->whereIn('estado', ['concluido', 'elaborado'])
                        ->get();

    }

    public function avisoPreventivo(){

        $movimiento = $this->movimientosRegistrales()
                                        ->whereHas('vario', function($q){
                                            $q->whereIn('servicio', ['D146', 'D157'])
                                                ->where('estado', 'activo')
                                                ->whereIn('acto_contenido', ['PRIMER AVISO PREVENTIVO', 'SEGUNDO AVISO PREVENTIVO']);
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
                                            $q->where('acto_contenido', 'ACLARACIÓN ADMINISTRATIVA');
                                        })
                                        ->whereNotIn('estado',['concluido', 'finalizado'])
                                        ->first();

        return $movimiento;

    }

    public function fideicomisoActivo(){

        return $this->fideicomisos()->where('fideicomisos.estado', 'activo')->first();

    }

}
