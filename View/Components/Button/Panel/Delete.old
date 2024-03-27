<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Button\Panel;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelContract;

/**
 * Class Delete.
 */
class Delete extends Component
{
    public string $method = 'delete';
    public array $attrs = [];

    /**
     * Undocumented function.
     */
    public function __construct(public PanelContract $panelContract, public string $tpl = 'v2')
    {
        $this->attrs['class'] = [];

        if (inAdmin()) {
            $this->attrs['button']['class'] = config('adm_theme::styles.delete.button.class', 'btn btn-primary mb-2 btn-danger btn-confirm-delete');
            $this->attrs['icon']['class'] = config('adm_theme::styles.delete.icon.class', 'far fa-trash-alt');
        } else {
            $this->attrs['button']['class'] = config('pub_theme::styles.delete.button.class', 'btn btn-primary mb-2 btn-danger btn-confirm-delete');
            $this->attrs['icon']['class'] = config('pub_theme::styles.delete.icon.class', 'far fa-trash-alt');
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
        return Gate::allows($this->method, $this->panelContract);
    }
}
