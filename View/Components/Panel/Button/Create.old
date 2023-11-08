<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Panel\Button;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelContract;

/**
 * Class Create.
 */
class Create extends Component
{
    public string $method = 'create';

    /**
     * Undocumented function.
     */
    public function __construct(public PanelContract $panelContract, public string $tpl = 'v2')
    {
    }

    /**
     * Undocumented function.
     */
    public function render(): Renderable
    {
        /**
         * @phpstan-var view-string
         */
        // $view = 'cms::components.panel.button.create.'.$this->tpl;
        $view = app(GetViewAction::class)->execute($this->tpl);
        $view_params = [
            'view' => $view,
        ];

        return view($view, $view_params);
    }

    public function shouldRender(): bool
    {
        return Gate::allows($this->method, $this->panelContract);
    }
}
