<?php

namespace App\Models;

use App\Traits\ModelosTrait;
use App\Models\CertificadoPersona;
use App\Models\MovimientoRegistral;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certificacion extends Model implements Auditable
{

    use HasFactory;
    use ModelosTrait;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = ['id', 'created_at', 'updated_At'];

    protected $fillable = ['movimiento_registral_id', 'servicio', 'numero_paginas', 'finalizado_en', 'firma', 'reimpreso_en', 'observaciones', 'folio_carpeta_copias', 'folio_real', 'movimiento_registral'];

    protected $casts =[
        'firma' => 'datetime:d-m-Y H:i:s',
        'finalizado_en' => 'datetime:d-m-Y H:i:s',
        'reimpreso_en' => 'datetime:d-m-Y H:i:s',
    ];

    public function movimientoRegistral(){
        return $this->belongsTo(MovimientoRegistral::class);
    }

    public function personas(){
        return $this->hasMany(CertificadoPersona::class);
    }

    /*
        Certificados de propiedad
        1 ->
        2 -> Propiedad
        3 -> Unico
        4 ->
        5 ->
    */

}
