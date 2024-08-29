<?php

declare(strict_types=1);

namespace Modules\Cms\Models;

<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Blog\Actions\ParentChilds\GetTreeOptions;
use Modules\Tenant\Models\Traits\SushiToJsons;
=======
>>>>>>> f6bb4c7 (🔧 (Headernav.php): Remove unnecessary code and fix conflicts in the file)
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
=======
use Illuminate\Database\Schema\Blueprint;
use Modules\Blog\Actions\ParentChilds\GetTreeOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
>>>>>>> 5eb580b (Check & fix styling)

/**
 * Modules\Cms\Models\Menu.
 *
 * @property int $id
 * @property string $name
 * @property array|null $items
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static \Modules\Blog\Database\Factories\MenuFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu withoutTrashed()
 *
 * @property string $title
 * @property int|null $parent_id
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\Modules\Cms\Models\Menu[] $children
 * @property int|null $children_count
 * @property \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Modules\Media\Models\Media> $media
 * @property int|null $media_count
 * @property Menu|null $parent
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\Modules\Cms\Models\Menu[] $ancestors The model's recursive parents.
 * @property int|null $ancestors_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\Modules\Cms\Models\Menu[] $ancestorsAndSelf The model's recursive parents and itself.
 * @property int|null $ancestors_and_self_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\Modules\Cms\Models\Menu[] $bloodline The model's ancestors, descendants and itself.
 * @property int|null $bloodline_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\Modules\Cms\Models\Menu[] $childrenAndSelf The model's direct children and itself.
 * @property int|null $children_and_self_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\Modules\Cms\Models\Menu[] $descendants The model's recursive children.
 * @property int|null $descendants_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\Modules\Cms\Models\Menu[] $descendantsAndSelf The model's recursive children and itself.
 * @property int|null $descendants_and_self_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\Modules\Cms\Models\Menu[] $parentAndSelf The model's direct parent and itself.
 * @property int|null $parent_and_self_count
 * @property Menu|null $rootAncestor The model's topmost parent.
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\Modules\Cms\Models\Menu[] $siblings The parent's other children.
 * @property int|null $siblings_count
 * @property \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\Modules\Cms\Models\Menu[] $siblingsAndSelf All the parent's children.
 * @property int|null $siblings_and_self_count
 *
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu whereTitle($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Menu withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 *
 * @property \Modules\Xot\Contracts\ProfileContract|null $creator
 * @property \Modules\Xot\Contracts\ProfileContract|null $updater
 *
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 *
 * @mixin \Eloquent
 */
class Menu extends BaseModel implements HasMedia
{
    use InteractsWithMedia;
<<<<<<< HEAD
    use SushiToJsons;
    use HasRecursiveRelationships;

    /** @var list<string> */
=======
    use \Orbit\Concerns\Orbital;
    use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

    /** @var string */
    public static $driver = 'json';

    /** @var array<int, string> */
>>>>>>> 75eb551 (up)
    protected $fillable = [
        'title',
        'items',
        'parent_id',
    ];

    public function getRows(): array
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return $this->getSushiRows();
=======
        $table->id();
        // $table->timestamps();
        $table->string('title');
        $table->json('items')->nullable();
        $table->unsignedBigInteger('parent_id')->nullable();
=======
        $table->id();

        $table->string('title');
        $table->json('items')->nullable();
        $table->unsignedBigInteger('parent_id')->nullable();
        // $table->timestamps();
>>>>>>> 3ab70fa (conflict)
        $table->string('created_by')->nullable();
        $table->string('updated_by')->nullable();
>>>>>>> f6bb4c7 (🔧 (Headernav.php): Remove unnecessary code and fix conflicts in the file)
    }

    protected array $schema = [
        'id' => 'integer',
        'title' => 'string',
        'parent_id' => 'integer',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'created_by' => 'string',
        'updated_by' => 'string',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'items' => 'array',
        ];
    }

    public static function getTreeMenuOptions(): array
    {
        $instance = new self;

        return app(GetTreeOptions::class)->execute($instance);
    }
}
