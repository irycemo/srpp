<?php

namespace App\Models;

use App\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Acto extends Model
{

    use HasFactory;
    use ModelosTrait;

    protected $fillable = ['acto', 'seccion'];

}
