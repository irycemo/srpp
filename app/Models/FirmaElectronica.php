<?php

namespace App\Models;

use App\Models\FolioReal;
use Illuminate\Support\Str;
use App\Models\MovimientoRegistral;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FirmaElectronica extends Model
{

    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static function boot()
    {

        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string)Str::uuid();
        });

    }

    public function getRouteKeyName(){
        return 'uuid';
    }

    public function folioReal(){
        return $this->belongsTo(FolioReal::class, 'folio_real');
    }

    public function movimientoRegistral(){
        return $this->belongsTo(MovimientoRegistral::class, 'movimiento_registral_id');
    }

}
