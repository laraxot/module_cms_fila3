<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;
use Modules\Cms\Services\PanelService;
use Modules\UI\Services\ThemeService;

/**
 * Undocumented class.
 */
class IncludeView extends Component
{
    public function __construct(public string $view, public array $vars = [])
    {
    }

    public function render(): Renderable
    {
        $panelContract = PanelService::make()->getRequestPanel();
        if (null == $panelContract) {
            return view('ui::empty');
        }
        // $views = ThemeService::getDefaultViewArray();
        $views = $panelContract->getViews();

        $view_tpl = $this->view;

        $views = collect($views)->map(
            fn ($item): string => $item.'.'.$view_tpl
        );

        /**
         * @phpstan-var view-string|null
         */
        $view_work = $views->first(
            fn ($view_check) => View::exists($view_check)
        );

        if (null === $view_work) {
            if (\in_array($view_tpl, ['topbar', 'bottombar', 'inner_page'], true)) {
                /**
                 * @phpstan-var view-string
                 */
                $view = 'ui:empty';

                return view($view);
                // throw new \Exception('$view_work is null');
            }

            dddx(['err' => 'view not Exists', 'views' => $views]);
        }

        if (null === $view_work) {
            throw new \Exception('$view_work is null');
        }

        return view($view_work, $this->vars);
    }
}
