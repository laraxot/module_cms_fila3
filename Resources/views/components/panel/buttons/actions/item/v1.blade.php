@if ($panel->getActions('item')->count() > 5)
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            <i class="fas fa-tools"></i>
        </button>
        <div class="dropdown-menu">

            @foreach ($panel->getActions('item') as $act)
                <x-button.action :action="$act" />
                {{-- <x-button.action :action="$act" /> --}}
            @endforeach
        </div>
    </div>
@else
    {{-- {{ dddx([$panel->getActions('item'), $panel]) }} --}}

    @foreach ($panel->getActions('item') as $act)
        {{--
        <x-button.action :action="$act" />
        --}}
        <x-button.action :action="$act" />
    @endforeach
@endif
