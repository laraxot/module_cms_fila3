<?php

declare(strict_types=1);

namespace Modules\Cms\Services;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use ReflectionException;
use Illuminate\Http\RedirectResponse;
use Session;
use ReflectionClass;
use stdClass;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\Cms\Contracts\PanelContract;
use Modules\Xot\Contracts\ModelContract;
use Modules\Xot\Contracts\ModelProfileContract;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Relations\CustomRelation;
use Modules\Xot\Services\StubService;
use Nwidart\Modules\Facades\Module;

/**
 * Modules\Cms\Services\PanelService.
 *
 * @property Model|ModelContract|ModelProfileContract $model
 *
 * @method Collection areas()
 */
class PanelService
{
    public $model;
    private ?PanelContract $panelContract = null;

    private static ?self $instance = null;

    public function __construct()
    {
        // ---
    }

    public static function getInstance(): self
    {
        if (!self::$instance instanceof \Modules\Cms\Services\PanelService) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function make(): self
    {
        return static::getInstance();
    }

    // 26     Property Modules\Cms\Services\PanelService::make()->$route_params is never read, only written.
    // private static array $route_params;

    /*
    public function __construct($model){
    $this->model=$model;
    }
     */

    // public function __construct(array $route_params) {
    // $this->route_params = $route_params;
    // static::$panel = $this->getByRouteParams($route_params);
    // static::$route_params =  $route_params;
    // }

    // public static function getInstance(): self {
    //    if (null === self::$_instance) {
    // $route_params = request()->route()->parameters();// 42     Cannot call method parameters() on mixed.
    //        $route_params = getRouteParameters();
    //        self::$_instance = new self($route_params);
    //    }

    //    return self::$_instance;
    // }

    /*
    public function test() {
        return static::$panel;
    }
    */
    public function setRequestPanel(?PanelContract $panelContract): self
    {
        $this->panelContract = $panelContract;

        return $this;
    }

    public function getRequestPanel(): ?PanelContract
    {
        return $this->panelContract;
    }

    /**
     * @param Model|ModelContract|ModelProfileContract $model
     *
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    public function get($model): PanelContract
    {
        $panel = $this->setModel($model)->panel();
        $post_type = $panel->postType();
        $name = Str::plural($post_type); // standard
        // $name = str($post_type)->plural(); // standard
        // $name = $post_type;
        $panel->setName((string) $name);

        return $panel;
    }

    public function getByUser(UserContract $userContract): PanelContract
    {
        $model = $userContract->newInstance();

        return $this->get($model);
    }

    /**
     * @param Model|ModelContract|ModelProfileContract $model
     */
    public function setModel($model): self
    {
        $this->model = $model;

        return static::getInstance();
    }

    // ret \Illuminate\Contracts\Foundation\Application|mixed|null
    /**
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    public function panel(): PanelContract
    {
        if (! \is_object($this->model)) {
            throw new Exception('model is not an object url:'.url()->current());
        }
        /*
        $class_full = get_class($this->model);
        $class_name = class_basename($this->model);
        //$class = Str::before($class_full, $class_name);
        $class = substr($class_full, 0, -strlen($class_name));
        $panel_class = $class.'Panels\\'.$class_name.'Panel';

        if (! class_exists($panel_class)) {
            $tmp = StubService::getByModel($this->model, 'panel', $create = true);
        }

        return app($panel_class)->setRow($this->model);
        */
        // backtrace(true);
        $panel_class = StubService::make()->setModelAndName($this->model, 'panel')->get();

        return app($panel_class)
            ->setRow($this->model)
            // ->setRouteParams($this->route_params)
        ;
    }

    public function imageHtml(?array $params): string
    {
        $res = $this->model->getAttributeValue('image_src');
        if (! \is_string($res)) {
            throw new Exception('['.__LINE__.']['.class_basename(self::class).']');
        }

        return $res;
    }

    public function tabs(): array
    {
        return $this->panel()->tabs();
    }

    // esempio parametro stringa 'area-1-menu-1'
    // rilascia il pannello dell'ultimo container (nell'esempio menu),
    // con parent il pannello del precedente container (nell'esempio area)
    public function getById(string $id): PanelContract
    {
        $piece = explode('-', $id);
        $route_params = [];
        $j = 0;
        $counter = \count($piece);
        for ($i = 0; $i < $counter; ++$i) {
            if (0 === $i % 2) {
                $route_params['container'.$j] = $piece[$i];
            } else {
                $route_params['item'.$j] = $piece[$i];
                ++$j;
            }
        }
        // [$containers, $items] = params2ContainerItem($route_params);
        // dddx([$route_params, $containers, $items]);
        $route_params['in_admin'] = true;

        return $this->getByParams($route_params);
    }

    public function getHomePanel(): PanelContract
    {
        /*
        $name = 'home';

        $model_class = config('morph_map.'.$name);
        if (null == $model_class || ! is_string($model_class) || ! class_exists($model_class)) {
            throw new Exception('['.$name.']['.__LINE__.']['.class_basename(__CLASS__).']');
        }
        $home = app($model_class);
        */

        $main_module = config('xra.main_module');

        $home = app('Modules\\'.$main_module.'\Models\Home');

        // $home = getModelByName('home');

        $params = getRouteParameters();

        try {
            $home = $home->firstOrCreate(['id' => 1]);
        } catch (Exception $e) {
            echo '<h3>'.$e->getMessage().'</h3>';
        }
        if (inAdmin() && isset($params['module'])) {
            $module = Module::find($params['module']);
            if (null === $module) {
                throw new Exception('module ['.$params['module'].'] not found');
            }
            $panel = '\Modules\\'.$module->getName().'\Models\Panels\_ModulePanel';
            $panel = app($panel);
            $panel->setRow($home);
            $panel->setName($params['module']);
        } else {
            $panel = self::make()->get($home);
            $panel->setName('home');
        }

        // ->firstOrCreate(['id' => 1]);
        // $panel = PanelService::make()->get($home);

        $customRelation = new CustomRelation(
            $home->newQuery(),
            $home,
            function ($relation): void {
                $relation->getQuery();
            },
            null,
            null
        );
        $panel->setRows($customRelation);

        return $panel;
    }

    /**
     * Function getByParams.
     */
    public function getByParams(?array $route_params): PanelContract
    {
        [$containers, $items] = params2ContainerItem($route_params);

        $in_admin = null;
        if (isset($route_params['in_admin'])) {
            $in_admin = $route_params['in_admin'];
        }
        if (null === $in_admin) {
            $in_admin = inAdmin();
        }

        // dddx([$containers, $items]);
        if ([] === $containers) {
            return $this->getHomePanel();
        }

        $row = null;
        // $first_container = Str::singular($containers[0]);
        $first_container = $containers[0];

        // dddx($route_params);

        if (isset($route_params['module'])) {
            $module_models = getModuleModels($route_params['module']);
            $model_class = collect($module_models)
                ->get($first_container);
            if (null === $model_class) {
                $model_class = collect($module_models)
                    ->get(Str::singular($first_container));
            }
            if (null !== $model_class) {
                $row = app($model_class);
            }
        }

        if (null === $row) {
            $row = getModelByName(Str::singular($first_container));
        }

        /*if (isset($items[0])) {
            $row = $row->find($items[0]);
        }*/

        $rows = new CustomRelation(
            $row->newQuery(),
            $row,
            function ($relation): void {
                $relation->getQuery();
            },
            null,
            null
        );

        $panel = self::make()->get($row);

        $panel->setRows($rows);

        $panel->setName(Str::plural($first_container)); // / !!! da controllare
        $i = 0;

        if (isset($items[0])) {
            $panel->setInAdmin($in_admin)->setItem($items[0]);
        }

        $panel_parent = $panel;
        $counter = \count($containers);
        for ($i = 1; $i < $counter; ++$i) {
            // dddx($panel_parent);
            $row_prev = $panel_parent->getRow();

            $types = Str::camel($containers[$i]);

            $rows = $row_prev->{$types}(); // Relazione
            // dddx(['row_prev' => $row_prev, 'panel_parent' => $panel_parent, 'types' => $types, 'rows' => $rows]);
            try {
                $row = $rows->getRelated(); // se relazione
            } catch (Exception) {  // se builder
                /*
                dddx(
                    [
                        'message' => $e->getMessage(),
                        'rows' => $rows,
                        'get_class_methods' => get_class_methods($rows),
                        'model' => $rows->getModel(),
                    ]
                );
                */
                $row = $rows->getModel();
            }

            $panel = self::make()->get($row);
            // $rows = $rows->getQuery();
            $panel->setRows($rows);
            $panel->setName($types);
            $panel->setParent($panel_parent);
            // echo '<pre>' . print_r($panel_parent->getRow()->getKey(), true) . '</pre>';
            if (isset($items[$i])) {
                // dddx(['panel' => $panel, 'item' => $items[$i]]);
                $panel->setInAdmin($in_admin)->setItem($items[$i]);
            }
            $panel_parent = $panel;
        }

        return $panel;
    }

    /**
     * @throws FileNotFoundException
     * @throws ReflectionException
     *
     * @return RedirectResponse|mixed
     */
    public function getByModel(Model $model)
    {
        $class_full = $model::class;
        $class_name = class_basename($model);
        $class = Str::before($class_full, $class_name);
        $panel = $class.'Panels\\'.$class_name.'Panel';
        if (class_exists($panel)) {
            if (! method_exists($panel, 'tabs')) {
                $this->updatePanel(['panel' => $panel, 'func' => 'tabs']);
            }

            return new $panel();
        }
        $this->createPanel($model);
        Session::flash('status', 'panel created');

        return redirect()->back();
    }

    /**
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    public function createPanel(Model $model): void
    {
        $class_full = $model::class;
        $class_name = class_basename($model);
        $class = Str::before($class_full, $class_name);
        $panel_namespace = $class.'Panels';
        // ---- creazione panel
        $reflectionClass = new ReflectionClass($model);
        $class_filename = $reflectionClass->getFileName();
        if (false === $class_filename) {
            throw new Exception('autoloader_reflector err');
        }
        $model_dir = \dirname($class_filename); // /home/vagrant/code/htdocs/lara/multi/laravel/Modules/LU/Models
        $stub_file = __DIR__.'/../Console/stubs/panel.stub';
        $stub = File::get($stub_file);
        $search = [];
        $fillables = $model->getFillable();
        $fields = [];
        foreach ($fillables as $fillable) {
            try {
                $input_type = $model->getConnection()->getDoctrineColumn($model->getTable(), $fillable)->getType()->getName();
            } catch (Exception) {
                $input_type = 'Text';
            }
            $tmp = new stdClass();
            // $tmp->type = (string) $input_type;// 311    Cannot cast 'Text'|Doctrine\DBAL\Types\Type to string.
            $tmp->type = $input_type;

            $tmp->name = $fillable;
            $fields[] = $tmp;
        }
        $dummy_id = $model->getRouteKeyName();
        /*
        Call to function is_array() with string will always evaluate to false
        if (is_array($dummy_id)) {
            echo '<h3>not work with multiple keys</h3>';
            $dummy_id = var_export($dummy_id, true);
        }
        */
        $replace = [
            'DummyNamespace' => $panel_namespace,
            'DummyClass' => $class_name.'Panel',
            'DummyFullModel' => $class_full,
            'dummy_id' => $dummy_id,
            'dummy_title' => 'title', // prendo il primo campo stringa
            'dummy_search' => var_export($search, true),
            'dummy_fields' => var_export($fields, true),
        ];
        $stub = str_replace(array_keys($replace), array_values($replace), (string) $stub);
        $panel_dir = $model_dir.'/Panels';
        File::makeDirectory($panel_dir, $mode = 0777, true, true);
        $panel_file = $panel_dir.'/'.$class_name.'Panel.php';
        File::put($panel_file, $stub);
    }

    /**
     * @throws FileNotFoundException
     * @throws ReflectionException
     */
    public function updatePanel(array $params = []): void
    {
        extract($params);
        if (! isset($func)) {
            dddx(['err' => 'func is missing']);

            return;
        }
        if (! isset($panel)) {
            dddx(['err' => 'panel is missing']);

            return;
        }
        $func_file = __DIR__.'/../Console/stubs/panels/'.$func.'.stub';
        $func_stub = File::get($func_file);
        $autoloader_reflector = new ReflectionClass($panel);
        $panel_file = $autoloader_reflector->getFileName();
        if (false === $panel_file) {
            throw new Exception('autoloader_reflector err');
        }

        $panel_stub = File::get($panel_file);
        $panel_stub = Str::replaceLast('}', $func_stub.\chr(13).\chr(10).'}', $panel_stub);
        File::put($panel_file, $panel_stub);
    }
}
