<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Form\Panel;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Services\PanelService;

class Lang extends Component
{
    public string $current_locale;
    public array $supported_locale;

    public array $attrs = [];

    public bool $show = false;

    public function __construct(public string $tpl = 'v1')
    {
        $this->current_locale = LaravelLocalization::getCurrentLocaleName();
        $this->supported_locale = LaravelLocalization::getSupportedLocales();
        if (class_exists(PanelService::class)) {
            $panel = PanelService::make()->getRequestPanel();
            if (! is_null($panel)) {
                $this->show = $panel->hasLang();
            } else {
                throw new Exception('['.__LINE__.']['.__FILE__.'], panel is null');
            }
        }
    }

    public function render(): Renderable
    {
        /**
         * @phpstan-var view-string
         */
        $view = app(GetViewAction::class)->execute($this->tpl);

        $view_params = [];

        return view($view, $view_params);
    }

    public function shouldRender(): bool
    {
        return $this->show;
    }
}
