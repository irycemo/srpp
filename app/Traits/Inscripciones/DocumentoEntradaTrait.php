<?php

namespace App\Traits\Inscripciones;

use App\Constantes\Constantes;
use App\Models\MovimientoRegistral;

trait DocumentoEntradaTrait{

    public $tipo_documento;
    public $autoridad_cargo;
    public $autoridad_nombre;
    public $numero_documento;
    public $fecha_emision;
    public $procedencia;
    public $documentos_entrada;
    public $cargos_autoridad;
    public $label_numero_documento = 'Número de documento';

    public function updatedTipoDocumento(){

        if($this->tipo_documento == ''){

            $this->reset('label_numero_documento');

        }else{

            $this->label_numero_documento = 'Número de ' . mb_strtolower($this->tipo_documento);

        }

    }

    public function cargarDocumentoEntrada(MovimientoRegistral $movimientoRegistral){

        $this->documentos_entrada = Constantes::DOCUMENTOS_DE_ENTRADA;
        $this->cargos_autoridad = Constantes::CARGO_AUTORIDAD;

        $this->tipo_documento = $movimientoRegistral->tipo_documento;
        $this->autoridad_cargo = $movimientoRegistral->autoridad_cargo;
        $this->autoridad_nombre = $movimientoRegistral->autoridad_nombre;
        $this->numero_documento = $movimientoRegistral->numero_documento;
        $this->fecha_emision = $movimientoRegistral->fecha_emision;
        $this->procedencia = $movimientoRegistral->procedencia;

    }

    public function actualizarDocumentoEntrada(MovimientoRegistral $movimientoRegistral)
    {

        $movimientoRegistral->update([
            'tipo_documento' => $this->tipo_documento,
            'autoridad_cargo' => $this->autoridad_cargo,
            'autoridad_nombre' => $this->autoridad_nombre,
            'numero_documento' => $this->numero_documento,
            'fecha_emision' => $this->fecha_emision,
            'procedencia' => $this->procedencia,
        ]);

    }

}
