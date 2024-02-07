<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Button;

use Illuminate\Contracts\View\View;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelContract;
use Modules\Xot\View\Components\XotBaseComponent;

/**
 * Class Crud.
 */
class Crud extends XotBaseComponent
{
    // public bool $has_pivot;

    /**
     * Undocumented function.
     */
    public function __construct(public PanelContract $panelContract, public string $tpl = 'v1')
    {
        // $this->has_pivot = isset($panel->getRow()->pivot);
    }

    /**
     * Undocumented function.
     */
    public function render(): View
    {
        /**
         * @phpstan-var view-string
         */
        $view = app(GetViewAction::class)->execute($this->tpl);
        $view_params = [
            'view' => $view,
        ];

        return view($view, $view_params);
    }
}
