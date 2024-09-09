<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\User;

trait ModelosTrait{

    public function creadoPor(){
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function actualizadoPor(){
        return $this->belongsTo(User::class, 'actualizado_por');
    }

    public function getCreatedAtAttribute(){
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at'])->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute(){
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['updated_at'])->format('d-m-Y H:i:s');
    }

    public static function boot() {

        parent::boot();

        static::creating(function($model){

            foreach ($model->attributes as $key => $value) {

                $model->{$key} = trim($value);

                $model->{$key} = $value === '' ? null : $value;
            }

        });

        static::updating(function($model){

            foreach ($model->attributes as $key => $value) {

                $model->{$key} = trim($value);

                $model->{$key} = $value === '' ? null : $value;
            }

        });

    }

}
