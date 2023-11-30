<?php

declare(strict_types=1);

namespace Modules\Cms\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
// //use Laravel\Scout\Searchable;
use Modules\Xot\Traits\Updater;

/**
 * Class BasePivot.
 */
abstract class BasePivot extends Pivot
{
    use Updater;

    /**
     * Indicates whether attributes are snake cased on arrays.
     *
     * @see  https://laravel-news.com/6-eloquent-secrets
     *
     * @var bool
     */
    public static $snakeAttributes = true;

    protected $perPage = 30;

    // use Searchable;

    /**
     * @var string
     */
    protected $connection = 'cms'; // this will use the specified database connection
    /**
     * @var array
     */
    protected $appends = [];
    
    /**
     * @var array<string, string>
     */
    protected $casts = ['created_at' => 'datetime', 'updated_at' => 'datetime'];
    
    /**
     * Undocumented variable.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * @var bool
     */
    public $incrementing = true;
}
