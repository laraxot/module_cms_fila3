<?php

declare(strict_types=1);

namespace Modules\Cms\Actions\Panel;

use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Modules\Cms\Contracts\PanelContract;
use Spatie\QueueableAction\QueueableAction;

final class StoreAction
{
    use QueueableAction;

    public function execute(PanelContract $panelContract, array $data): PanelContract
    {
        $row = $panelContract->getRow();

        $rules = $panelContract->getRules('create');

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

        $row = app('\\'.$act)->execute($row, $data, $rules);
        $panelContract = $panelContract->setRow($row);
        $parent = $panelContract->getParent();
        if (\is_object($parent)) {
            $parent_row = $parent->getRow();
            $pivot_data = [];
            if (isset($data['pivot'])) {
                $pivot_data = $data['pivot'];
            }

            if (! isset($pivot_data['user_id'])) {
                $pivot_data['user_id'] = \Auth::id();
            }

            try {
                // *
                $types = $panelContract->getName();
                $tmp_rows = $parent_row->$types();
                $tmp = $tmp_rows->save($row, $pivot_data);
                // */

                /*
                dddx([
                    '$tmp_rows'=>$tmp_rows,   // Illuminate\Database\Eloquent\Relations\BelongsToMany
                    '$panel->getRows()'=>$panel->getRows(), //Illuminate\Database\Eloquent\Builder
                ]);
                */

                // 55  Call to an undefined method Illuminate\Database\Eloquent\Builder::save().
                // $tmp = $panel->getRows()->save($row, $pivot_data); //??

                // $tmp = $panel->getRows()->create($pivot_data); //??
                /*
                Model
                BelongsToMany
                HasOneOrMany
                */
            } catch (\Exception $e) {
                // message: "Call to undefined method Illuminate\Database\Eloquent\Builder::save()"
                dddx(
                    [
                        'e' => $e,
                        'panel' => $panelContract,
                        'methods' => get_class_methods($panelContract->getRows()),
                    ]
                );
                /*
                $this->row = $row;
                $func = 'saveParent'.Str::studly(class_basename($parent_row->$types()));
                $tmp = $this->$func();
                */
            }

            // $tmp=$item->$types()->attach($row->getKey(),$pivot_data);
            // $tmp = $item->$types()->save($row, $pivot_data);
        }

        return $panelContract;
    }
}
