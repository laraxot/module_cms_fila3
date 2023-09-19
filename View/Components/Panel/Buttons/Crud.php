<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Panel\Buttons;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;
use Modules\Cms\Actions\GetStyleClassByViewAction;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelContract;

/**
 * Class Std.
 */
class Crud extends Component
{
    public string $view;
    public array $attrs = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public PanelContract $panelContract, public string $tpl = 'v1')
    {
        $this->view = app(GetViewAction::class)->execute($this->tpl);
        $this->attrs['class'] = app(GetStyleClassByViewAction::class)->execute($this->view);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): Renderable
    {
        /**
         * @phpstan-var view-string
         */
        // $view = 'cms::components.panel.buttons.crud.'.$this->tpl;
        $view = $this->view;

        $view_params = [
            'view' => $view,
        ];

        return view($view, $view_params);
    }
}
