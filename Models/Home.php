<?php

/**
 * ---.
 */

declare(strict_types=1);

namespace Modules\Cms\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Cms\Database\Factories\HomeFactory;
use Modules\Xot\Models\Traits\WidgetTrait;
use Modules\Xot\Models\Widget;
use Sushi\Sushi;

/**
 * Modules\Cms\Models\Home.
 *
 * @property int|null                $id
 * @property string|null             $name
 * @property string|null             $icon_src
 * @property string|null             $created_by
 * @property string|null             $updated_by
 * @property Collection<int, Widget> $containerWidgets
 * @property int|null                $container_widgets_count
 * @property Collection<int, Widget> $widgets
 * @property int|null                $widgets_count
 * @method static HomeFactory  factory($count = null, $state = [])
 * @method static Builder|Home newModelQuery()
 * @method static Builder|Home newQuery()
 * @method static Builder|Home ofLayoutPosition($layout_position)
 * @method static Builder|Home query()
 * @method static Builder|Home whereCreatedBy($value)
 * @method static Builder|Home whereIconSrc($value)
 * @method static Builder|Home whereId($value)
 * @method static Builder|Home whereName($value)
 * @method static Builder|Home whereUpdatedBy($value)
 * @mixin IdeHelperHome
 * @mixin \Eloquent
 */
class Home extends BaseModel
{
    use Sushi;
    // use WidgetTrait;

    /**
     * @var string[]
     */
    protected $fillable = ['id', 'name', 'icon_src', 'created_by', 'updated_by'];

    /**
     * Undocumented variable.
     *
     * @var array
     */
    protected $rows = [
        [
            'id' => 'home',
            'name' => 'New York',
            'icon_src' => '',
            'created_by' => 'xot',
            'updated_by' => 'xot',
        ],
    ];
}
