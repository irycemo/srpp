<?php

namespace App\Livewire\Certificaciones\CertificadoPropiedad;

use App\Traits\CalcularDiaElaboracionTrait;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\Inscripciones\ColindanciasTrait;
use App\Traits\Certificaciones\CertificadoPropiedadTrait;

class CertificadoPropiedad extends Component
{

    use CertificadoPropiedadTrait;
    use ColindanciasTrait;
    use CalcularDiaElaboracionTrait;

    public function generarCertificado(){

        if(!auth()->user()->hasRole(['Jefe de departamento certificaciones']) && $this->certificacion->movimientoRegistral->distrito != '02 Uruapan'){

            if($this->calcularDiaElaboracion($this->certificacion->movimientoRegistral)) return;

        }

        try{

            DB::transaction(function (){

                $this->guardarColindancias($this->certificacion->movimientoRegistral->folioReal->predio);

                $this->certificacion->movimientoRegistral->estado = 'elaborado';
                $this->certificacion->movimientoRegistral->save();

                $this->certificacion->actualizado_por = auth()->user()->id;
                $this->certificacion->tipo_certificado = 2;
                $this->certificacion->temporalidad = $this->temporalidad;
                $this->certificacion->observaciones_certificado = $this->observaciones;
                $this->certificacion->save();

                if($this->certificacion->movimientoRegistral->fecha_entrega > now()){

                    $this->certificacion->audits()->latest()->first()->update(['tags' => 'Generó certificado anticipadamente.']);

                }

                $this->procesarPersona($this->nombre, $this->ap_paterno, $this->ap_materno);

            });

            $this->dispatch('imprimir_propiedad', ['certificacion' => $this->certificacion->movimientoRegistral->id]);

        } catch (\Throwable $th) {

            Log::error("Error al generar certificado de propiedad por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        if($this->certificacion->movimientoRegistral->folioReal)
            $this->cargarColindancias($this->certificacion->movimientoRegistral->folioReal->predio);

    }

    public function render()
    {
        return view('livewire.certificaciones.certificado-propiedad.certificado-propiedad');
    }
}
