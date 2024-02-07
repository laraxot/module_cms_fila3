<?php

declare(strict_types=1);

namespace Modules\Cms\Actions\Panel;

use Modules\Cms\Contracts\PanelContract;
use Spatie\QueueableAction\QueueableAction;

final class DetachAction
{
    use QueueableAction;

    public function execute(PanelContract $panelContract, array $data): PanelContract
    {
        $model = $panelContract->getRow();
        $rules = [];
        $act = str_replace('\Panel\\', '\Model\\', self::class);
        $act = str_replace('\Cms\\', '\Xot\\', $act);

        app('\\'.$act)->execute($model, $data, $rules);
        /*
        if (method_exists($panel, 'detachCallback')) {
            $panel->detachCallback(['row' => $row]);
        }
        */

        return $panelContract;
    }
}
