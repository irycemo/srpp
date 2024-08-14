<?php

namespace App\Models;

use App\Models\Predio;
use App\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Colindancia extends Model
{
    use HasFactory;
    use ModelosTrait;

    protected $fillable = ['predio_id', 'viento', 'longitud', 'descripcion', 'creado_por', 'actualizado_por'];

    public function predio(){
        return $this->belongsTo(Predio::class);
    }

}
