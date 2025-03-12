<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asociacion extends Model
{

    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'permorales';

    public $timestamps = false;

    public function movimientos(){
        return $this->hasMany(AsociacionMovimiento::class, 'idPM');
    }

}
