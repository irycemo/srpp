<?php

namespace App\Models;

use App\Models\Escritura;
use App\Models\Colindancia;
use App\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Predio extends Model implements Auditable
{

    use HasFactory;
    use ModelosTrait;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = ['id', 'created_at', 'updated_At'];

    public function propietarios(){
        return $this->hasMany(Propietario::class);
    }

    public function colindancias(){
        return $this->hasMany(Colindancia::class);
    }

    public function escritura(){
        return $this->belongsTo(Escritura::class, 'escritura_id');
    }

}
