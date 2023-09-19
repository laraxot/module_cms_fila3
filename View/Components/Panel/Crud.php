<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Panel;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelContract;

/**
 * Class Std.
 */
class Crud extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public PanelContract $panelContract, public LengthAwarePaginator $lengthAwarePaginator, public string $tpl = 'v1')
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): Renderable
    {
        /**
         * @phpstan-var view-string
         */
        $view = app(GetViewAction::class)->execute($this->tpl);
        $fields = $this->panelContract->getFields('index');

        $view_params = [
            'view' => $view,
            'fields' => $fields,
            // 'rows' => $this->panel->rows()->paginate(20),
        ];

        return view($view, $view_params);
    }
}
