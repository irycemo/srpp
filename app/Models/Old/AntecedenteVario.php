<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntecedenteVario extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'antecedentesvar';

}
