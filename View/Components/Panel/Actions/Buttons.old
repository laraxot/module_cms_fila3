<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Panel\Actions;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Modules\Cms\Actions\GetViewAction;

class Buttons extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public Collection $acts, public string $tpl = 'v1')
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
        // $view = 'cms::components.panel.actions.buttons.'.$this->tpl;
        $view = app(GetViewAction::class)->execute($this->tpl);

        $view_params = [
            'view' => $view,
        ];

        return view($view, $view_params);
    }

    // public static function resolve(array $acts, string $tpl = 'v1') {
    // dddx(['a' => $a]);
    // }
}
