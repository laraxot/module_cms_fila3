<?php

declare(strict_types=1);

namespace Modules\Cms\Models;

use Illuminate\Database\Eloquent\Builder;
use Modules\Cms\Database\Factories\ModuleFactory;
use Nwidart\Modules\Facades\Module as NwModule;
use Sushi\Sushi;

/**
 * Modules\Cms\Models\Module.
 *
 * @property int         $id
 * @property string|null $name
 *
 * @method static ModuleFactory  factory($count = null, $state = [])
 * @method static Builder|Module newModelQuery()
 * @method static Builder|Module newQuery()
 * @method static Builder|Module query()
 * @method static Builder|Module whereId($value)
 * @method static Builder|Module whereName($value)
 *
 * @mixin IdeHelperModule
 *
<<<<<<< HEAD
 * @property \Modules\Xot\Contracts\ProfileContract|null $creator
 * @property \Modules\Xot\Contracts\ProfileContract|null $updater
=======
 * @property \Modules\Fixcity\Models\Profile|null $creator
 * @property \Modules\Fixcity\Models\Profile|null $updater
>>>>>>> 2cd4553 (Check & fix styling)
 *
 * @mixin \Eloquent
 */
class Module extends BaseModel
{
    use Sushi;

    /** @var list<string> */
    protected $fillable = [
        'id', 'name',
    ];

    public function getRows(): array
    {
        $modules = NwModule::getByStatus(1);
        $rows = [];
        $i = 1;
        foreach ($modules as $module) {
            $tmp = [
                'id' => $i++,
                'name' => $module->getName(),
            ];
            $rows[] = $tmp;
        }

        return $rows;
    }

    /**
     * Undocumented function.
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
