<x-pagination :rows="$rows" />
@php
    if (!isset($_panel) && isset($panel)) {
        $_panel = $panel;
    }
@endphp
<div class="table-responsive">
    {{--  <h3> $rows->count() </h3> --}}
    <table class="table table-hover table-nowrap">
        @foreach ($rows as $row)
            @php
                $row_panel = $_panel->newPanel($row);
            @endphp
            @if ($loop->first)
                <form action="">
                    <thead class="table-light">
                        <tr>
                            @if ($_panel->getActions('check')->count() > 0)
                                <th class="p-0">
                                    <div class="btn-group">
                                        <select name="_act" id="" class="form-select">
                                            @foreach ($_panel->getActions('check') as $act)
                                                {{-- <option value="{{ $act->name }}">{{ $act->name }}</option> --}}
                                                <option value="{{ $act->name }}" class="p-0">
                                                    {!! $act->icon !!}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-primay btn-sm">Esegui</button>
                                    </div>
                                </th>
                            @endif
                            @foreach ($fields as $field)
                                {{--
                                <th scope="col">{{ $field->name }}</th>
                                --}}
                                <x-table.th.spatie :name="$field->name" />
                            @endforeach
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
            @endif
            <tr>
                @if ($_panel->getActions('check')->count() > 0)
                    <td>
                        <div class="d-flex justify-content-center">
                            <x-include-view view="checkbox_action_check" :vars="get_defined_vars()" />
                        </div>
                        {{-- <div class="text-end">
                            <div class="form-check form-switch me-n2">
                                {{ Form::checkbox('ids[]', $row->id, false, ['class' => 'form-check-input']) }}
                            </div>
                        </div> --}}
                        {{-- {{ Form::checkbox('ids[]', $row->id, false, ['class' => 'form-check-input']) }} --}}
                        {{-- <x-input type="checkbox" name="ids[]" :options="[$row->id]" /> --}}
                    </td>
                @endif

                @foreach ($fields as $field)
                    <td>
                        <x-input.freeze :field="$field" :row="$row" />
                        @if ($loop->first)
                            <x-panel.buttons.actions.item :panel="$row_panel" />
                        @endif
                    </td>
                @endforeach

                <td class="text-end">
                    <x-panel.buttons.crud :panel="$row_panel" />
                </td>
            </tr>
            @if ($loop->last)
                </form>
            @endif
        @endforeach
        </tbody>
    </table>
</div>
<div class="card-footer border-0 py-5">
    <span class="text-muted text-sm">Showing {{ $rows->count() }} items out of {{ $rows->total() }} results
        found</span>
</div>
<x-pagination :rows="$rows" />
