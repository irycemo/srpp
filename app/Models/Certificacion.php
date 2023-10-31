<?php

namespace App\Models;

use App\Traits\ModelosTrait;
use App\Models\MovimientoRegistral;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certificacion extends Model implements Auditable
{

    use HasFactory;
    use ModelosTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['movimiento_registral_id', 'servicio', 'numero_paginas', 'finalizado_en', 'firma', 'reimpreso_en', 'observaciones', 'folio_carpeta_copias'];

    protected $casts =[
        'firma' => 'datetime:d-m-Y H:i:s',
        'finalizado_en' => 'datetime:d-m-Y H:i:s',
        'reimpreso_en' => 'datetime:d-m-Y H:i:s',
    ];

    public function movimientoRegistral(){
        return $this->belongsTo(MovimientoRegistral::class);
    }

}
