<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GravamenOld extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'gravamenes';

}
