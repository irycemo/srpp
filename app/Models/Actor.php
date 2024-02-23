<?php

namespace App\Models;

use App\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Actor extends Model implements Auditable
{

    use HasFactory;
    use ModelosTrait;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function actorable(){
        return $this->morphTo();
    }

    public function persona(){
        return $this->belongsTo(Persona::class);
    }

    public function representados(){
        return $this->hasMany(Actor::class, 'representado_por');
    }

}
