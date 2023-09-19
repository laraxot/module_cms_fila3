<?php

declare(strict_types=1);

namespace Modules\Cms\Models\Panels\Policies;

use Modules\Cms\Contracts\PanelContract;
use Modules\Xot\Contracts\UserContract;

class _ModulePanelPolicy extends XotBasePanelPolicy
{
    public function showModelsModuleMenu(UserContract $userContract, PanelContract $panelContract): bool
    {
        return false;
    }

    public function menuBuilder(UserContract $userContract, PanelContract $panelContract): bool
    {
        return true;
    }
}
