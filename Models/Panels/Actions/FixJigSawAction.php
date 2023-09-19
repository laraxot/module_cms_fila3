<?php

declare(strict_types=1);

namespace Modules\Cms\Models\Panels\Actions;

use Modules\Cms\Actions as SpatieActions;
use Nwidart\Modules\Facades\Module as NwModule;

/**
 * Class ArtisanAction.
 */
class FixJigSawAction extends XotBasePanelAction
{
    public bool $onContainer = true;

    public string $icon = '<i class="far fa-file-excel fa-1x"></i>Fix JigSaw';

    /**
     * @return void
     */
    public function handle(): string
    {
        $modules = NwModule::all();
        foreach ($modules as $module) {
            // dddx($module); //Nwidart\Modules\Laravel\Module
            $files = app(SpatieActions\Module\FixJigSawByModuleAction::class)->execute($module);
            // break;
            echo '<h3>'.$module->getName().'</h3>';
            echo '<pre>'.print_r($files, true).'</pre>';
        }

        return '<h3>+Done</h3>';
    }

    // end handle
}
