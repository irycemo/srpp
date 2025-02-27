<div>

    <div class="bg-white rounded-lg p-4 shadow-lg mb-4">

        <x-input-group for="inscripcion.descripcion_acto" label="Descripción del acto" :error="$errors->first('inscripcion.descripcion_acto')" class="w-full lg:w-1/4 mx-auto">

            <textarea class="bg-white rounded text-xs w-full  @error('inscripcion.descripcion_acto') border-1 border-red-500 @enderror" rows="4" wire:model="inscripcion.descripcion_acto"></textarea>

        </x-input-group>

    </div>

</div>
