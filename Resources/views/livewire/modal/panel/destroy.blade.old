<x-modal.skin on-submit="delete" :content-padding="false">
    <x-slot name="title">Delete Model</x-slot>

    Are you sure to delete id {{ $model_id }} of {{ $model_type }} ?

    <x-slot name="buttons">

        {{-- <button type="submit" class="btn btn-primary" wire:click="delete()">
            Delete
        </button> --}}
        <x-button type="submit" wire:click="delete()" class="btn btn-success rounded-5 m-1">Delete</x-button>

        {{-- <button type="button" class="btn btn-danger" wire:click="$emit('modal.close')">
            Cancel
        </button> --}}
        <x-button type="button" wire:click="$emit('modal.close')" class="btn btn-danger m-1">Cancel</x-button>
    </x-slot>
</x-modal.skin>
