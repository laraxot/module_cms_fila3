<div role="group" aria-label="Actions" class="btn-group btn-group-sm">
    @foreach ($panel->getItemTabs() as $tab)
        {{-- <x-button :attrs="get_object_vars($tab)"></x-button> --}}
        <x-button.link :link="$tab"></x-button.link>
    @endforeach
</div>
<br />
<div role="group" aria-label="Actions" class="btn-group btn-group-sm">
    <x-button.panel type="edit" :panel="$panel" />
    <x-button.panel type="delete" :panel="$panel" />
    <x-button.panel type="detach" :panel="$panel" />
    <x-button.panel type="show" :panel="$panel" />
</div>
