<?php

declare(strict_types=1);

namespace Modules\Cms\Actions\Panel;

use Modules\Cms\Contracts\PanelContract;
use Spatie\QueueableAction\QueueableAction;

final class UpdateAction
{
    use QueueableAction;

    public function execute(PanelContract $panelContract, array $data): PanelContract
    {
        // dddx($panel);
        $model = $panelContract->getRow();

        $rules = $panelContract->rules('edit');
        $act = str_replace('\Panel\\', '\Model\\', self::class);
        $act = str_replace('\Cms\\', '\Xot\\', $act);

        $parent = $panelContract->getParent();
        if ($parent != null) {
            $rows = $panelContract->rows;
            if (method_exists($rows, 'getForeignKeyName') && method_exists($rows, 'getParentKey')) {
                $foreign_key_name = $rows->getForeignKeyName();
                $parent_key = $rows->getParentKey();
                $data[$foreign_key_name] = $parent_key;
            } else {
            }
        }

        app('\\'.$act)->execute($model, $data, $rules);

        return $panelContract;
    }
}
