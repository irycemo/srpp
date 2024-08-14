<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificadoPersona extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_At'];

    public function persona(){
        return $this->belongsTo(Persona::class);
    }

}
