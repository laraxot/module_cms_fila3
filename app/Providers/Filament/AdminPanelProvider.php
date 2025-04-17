<?php

declare(strict_types=1);

namespace Modules\Cms\Providers\Filament;

use Filament\Panel;
use Filament\SpatieLaravelTranslatablePlugin;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Cms';

    public function panel(Panel $panel): Panel
    {
        $panel->plugins([
            SpatieLaravelTranslatablePlugin::make(),
        ]);

        return parent::panel($panel);
    }
}
