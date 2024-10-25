<?php

namespace App\Models;

use App\Models\FolioReal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Antecedente extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function folioReal(){
        return $this->belongsTo(FolioReal::class, 'folio_real');
    }

    public function folioRealAntecedente(){
        return $this->belongsTo(FolioReal::class, 'folio_real_antecedente');
    }

}
