<?php

namespace App\Models;

use App\Traits\ModelosTrait;
use App\Models\MovimientoRegistral;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cancelacion extends Model implements Auditable
{

    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use ModelosTrait;

    protected $guarded = ['id', 'created_at', 'updated_At'];

    public function movimientoRegistral(){
        return $this->belongsTo(MovimientoRegistral::class);
    }

    public function gravamenCancelado(){
        return $this->belongsTo(MovimientoRegistral::class, 'gravamen');
    }

}
