<?php

namespace App\Models;

use App\Models\Predio;
use App\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Escritura extends Model implements Auditable
{
    use HasFactory;
    use ModelosTrait;
    use \OwenIt\Auditing\Auditable;

    public $guarded = ['id', 'created_at', 'updated_at'];

    public function predios(){
        return $this->hasMany(Predio::class);
    }
}
