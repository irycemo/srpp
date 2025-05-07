<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pregunta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PreguntaLeida extends Model
{

    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_At'];

    protected $table = 'pregunta_user';

    public function usuario(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pregunta(){
        return $this->belongsTo(Pregunta::class);
    }

    public function getCreatedAtAttribute(){
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at'])->format('d-m-Y H:i:s');
    }

}
