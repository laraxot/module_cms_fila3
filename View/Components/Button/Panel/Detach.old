<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Button\Panel;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelContract;

/**
 * Class Detach.
 */
class Detach extends Component
{
    public string $method = 'delete';
    public array $attrs = [];

    /**
     * Undocumented function.
     */
    public function __construct(public PanelContract $panelContract, public string $tpl = 'v1')
    {
        $this->attrs['class'] = [];

        if (inAdmin()) {
            $this->attrs['button']['class'] = config('adm_theme::styles.detach.button.class', 'btn btn-primary mb-2 btn-danger');
            $this->attrs['icon']['class'] = config('adm_theme::styles.detach.icon.class', 'fas fa-unlink');
        } else {
            $this->attrs['button']['class'] = config('pub_theme::styles.detach.button.class', 'btn btn-primary mb-2 btn-danger');
            $this->attrs['icon']['class'] = config('pub_theme::styles.detach.icon.class', 'fas fa-unlink');
        }
    }

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
        if (property_exists($this->panelContract->getRow(), 'pivot') && null !== $this->panelContract->getRow()->pivot) {
            return false;
        }

        return Gate::allows($this->method, $this->panelContract);
    }
}
