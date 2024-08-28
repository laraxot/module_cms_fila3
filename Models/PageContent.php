<?php

declare(strict_types=1);

namespace Modules\Cms\Models;

use Modules\Tenant\Models\Traits\SushiToJsons;
use Spatie\Translatable\HasTranslations;

/**
 * Modules\Cms\Models\PageContent
 * @property string                                                                                                     $blocks
 */

class PageContent extends BaseModel
{
    use HasTranslations;
    use SushiToJsons;

    protected $fillable = [
        'name',
        'slug',
        'blocks',
    ];

    /** @var array<int, string> */
    public $translatable = [
        'name',
        'blocks',
    ];

    public function getRows(): array
    {
        return $this->getSushiRows();
    }

    protected array $schema = [
        'id' => 'integer',
        'name' => 'json',
        'slug' => 'string',

        'blocks' => 'json',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',

        'created_by' => 'string',
        'updated_by' => 'string',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @return array<string, string> */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'name' => 'string',
            'slug' => 'string',
            'uuid' => 'string',
            'blocks' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
