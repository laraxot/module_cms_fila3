<x-filament-panels::page>

    <x-filament-panels::form wire:submit="updateData">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getUpdateFormActions()"
        />

    </x-filament-panels::form>

</x-filament-panels::page>
