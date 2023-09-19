<?php

declare(strict_types=1);

namespace Modules\Cms\Models\Panels\Actions;

use Illuminate\Contracts\View\View;

class SortAction extends XotBasePanelAction
{
    public bool $onContainer = true; // onlyContainer

    public string $icon = '<i class="fas fa-sort"></i>';

    public function handle(): View
    {
        /**
         * @phpstan-var view-string
         */
        $view = 'cms::admin.index.acts.sort';
        $view_params = [
            'view' => $view,
        ];

        return view($view, $view_params);
    }
}
