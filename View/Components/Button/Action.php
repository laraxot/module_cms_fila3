<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Button;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Modules\Cms\Actions\GetStyleClassByViewAction;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelActionContract;

/**
 * Class Action.
 */
class Action extends Component
{
    // public string $method = 'show';
    public array $attrs = [];
    
    public string $policy_name;
    
    public string $view;
    
    public string $icon;

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function __construct(public PanelActionContract $panelActionContract, public string $tpl = 'v1')
    {
        $this->policy_name = $panelActionContract->getPolicyName();

        $this->view = app(GetViewAction::class)->execute($this->tpl);

        $this->attrs['class'] = app(GetStyleClassByViewAction::class)->execute($this->view);

        $this->attrs['href'] = $this->panelActionContract->url();
        $this->attrs['data-toggle'] = 'tooltip';
        // $this->attrs['title'] = $action->getName();
        $this->attrs['title'] = $panelActionContract->getTitle();
        $this->attrs['type'] = 'button';
        $this->attrs['onclick'] = $panelActionContract->getOnClick();

        $this->icon = $panelActionContract->icon;
    }

    /**
     * Undocumented function.
     */
    public function render(): Renderable
    {
        /**
         * @phpstan-var view-string
         */
        $view = $this->view;

        $view_params = [
            'view' => $view,
        ];

        return view($view, $view_params);
    }

    public function shouldRender(): bool
    {
        return Gate::allows($this->policy_name, $this->panelActionContract->panel);
    }
}
