<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Button;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelContract;

/**
 * Class Show.
 */
class Show extends Component
{
    public string $method = 'show';
    
    public array $attrs = [];

    /**
     * Undocumented function.
     */
    public function __construct(public PanelContract $panelContract, public string $tpl = 'v1')
    {
        $this->attrs['class'] = [];

        if (inAdmin()) {
            $this->attrs['button']['class'] = config('adm_theme::styles.show.button.class', 'btn btn-primary mb-2');
            $this->attrs['icon']['class'] = config('adm_theme::styles.show.icon.class', 'far fa-eye');
        } else {
            $this->attrs['button']['class'] = config('pub_theme::styles.show.button.class', 'btn btn-primary mb-2');
            $this->attrs['icon']['class'] = config('pub_theme::styles.show.icon.class', 'far fa-eye');
        }
    }

    /**
     * Undocumented function.
     */
    public function render(): Renderable
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

    public function shouldRender(): bool
    {
        return Gate::allows($this->method, $this->panelContract);
    }
}
