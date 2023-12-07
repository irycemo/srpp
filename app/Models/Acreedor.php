<?php

namespace App\Models;

use App\Models\Persona;
use App\Models\Gravamen;
use App\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Acreedor extends Model implements Auditable
{
    use HasFactory;
    use ModelosTrait;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function gravamen(){
        return $this->belongsTo(Gravamen::class);
    }

    public function persona(){
        return $this->belongsTo(Persona::class);
    }

}
