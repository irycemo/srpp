<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Propiedadold extends Model  implements Auditable
{

    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $connection = 'mysql2';

    protected $table = 'propiedades';

    public $timestamps = false;

}
