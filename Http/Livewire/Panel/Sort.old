<?php

declare(strict_types=1);

namespace Modules\Cms\Http\Livewire\Panel;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Livewire\Component;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelContract;
use Modules\Cms\Services\PanelService;
use Modules\Xot\Contracts\ModelWithPosContract;

/**
 * Class Sort.
 *
 * @property PanelContract $panel
 * @property Collection    $rows
 */
class Sort extends Component
{
    public array $routeParams = [];

    public array $data = [];

    /**
     * Undocumented function.
     */
    public function mount(): void
    {
        $this->routeParams = getRouteParameters();
        $this->data = request()->all();
    }

    /**
     * Undocumented function.
     */
    public function getPanelProperty(): PanelContract
    {
        return PanelService::make()->getByParams($this->routeParams);
    }

    public function getRowsProperty(): Collection
    {
        return $this->panel->rows($this->data)
            ->orderBy('pos')
            ->get();
    }

    public function render(): Renderable
    {
        /**
         * @phpstan-var view-string
         */
        $view = app(GetViewAction::class)->execute();
        $view_params = [
            'view' => $view,
        ];

        return view($view, $view_params);
    }

    public function updateTaskOrder(array $list): void
    {
        // dddx([$a]);
        /*
          7 => array:2 [▼
        "order" => 8
        "value" => "4418"
        ]
        */

        // https://github.com/spatie/eloquent-sortable
        foreach ($list as $v) {
            /**
             * @var ModelWithPosContract
             */
            $row = $this->rows->firstWhere('id', $v['value']);
            /*
            $row->pos = $v['order'];
            $row->save();
            */
            $up = [
                'pos' => $v['order'],
            ];
            $row->update($up);
        }

        session()->flash('message', 'Sort successfully ');
    }
}
