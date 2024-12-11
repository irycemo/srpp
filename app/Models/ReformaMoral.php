<?php

namespace App\Models;

use App\Models\Actor;
use App\Traits\ModelosTrait;
use App\Models\MovimientoRegistral;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReformaMoral extends Model
{

    use HasFactory;
    use ModelosTrait;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function movimientoRegistral(){
        return $this->belongsTo(MovimientoRegistral::class);
    }

    public function actores(){
        return $this->morphMany(Actor::class, 'actorable');
    }

}

