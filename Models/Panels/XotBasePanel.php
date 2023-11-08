<?php

declare(strict_types=1);

namespace Modules\Cms\Models\Panels;

use App;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
// ----------  SERVICES --------------------------
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Modules\Cms\Actions\Panel\UpdateAction;
use Modules\Cms\Contracts\PanelContract;
use Modules\Cms\Contracts\PanelPresenterContract;
use Modules\Cms\Contracts\RowsContract;
use Modules\Cms\Models\Panels\Actions\XotBasePanelAction;
use Modules\Cms\Presenters\HtmlPanelPresenter;
use Modules\Cms\Presenters\PdfPanelPresenter;
use Modules\Cms\Presenters\XlsPanelPresenter;
use Modules\Cms\Services\PanelActionService;
use Modules\Cms\Services\PanelFormService;
use Modules\Cms\Services\PanelRouteService;
use Modules\Cms\Services\PanelService;
use Modules\Cms\Services\PanelTabService;
use Modules\Cms\Services\RouteService;
use Modules\Cms\Services\RowsService;
use Modules\UI\Datas\FieldData;
use Modules\UI\Services\FieldService;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Jobs\PanelCrud\StoreJob;
use Modules\Xot\Services\ChainService;
use Modules\Xot\Services\FileService;
use Modules\Xot\Services\ImageService;
use Modules\Xot\Services\PolicyService;
use Modules\Xot\Services\StubService;
use Spatie\LaravelData\DataCollection;
use Spatie\QueryBuilder\QueryBuilder;
use stdClass;

/**
 * Class XotBasePanel.
 *
 * Modules\Cms\Models\Panels\XotBasePanel.
 *
 * @property Model $row
 */
abstract class XotBasePanel implements PanelContract
{
    protected static string $model;

    /**
     * @var Model
     */
    public $row;
    /**
     * in certe relazioni c'e' il where se passo al builder perdo i "with".
     *
     * @var Relation|Builder
     */
    public $rows;

    public ?Builder $query = null;

    public ?string $name = null;

    public ?PanelContract $parent = null;

    public ?bool $in_admin = null;

    public array $route_params = [];

    public PanelPresenterContract $presenter;

    public PanelFormService $form;

    public PanelRouteService $route;

    public function __construct(/* PanelPresenterContract $presenter, */ PanelRouteService $panelRouteService)
    {
        // $this->presenter = $presenter->setPanel($this);
        $this->presenter = (new HtmlPanelPresenter())->setPanel($this);

        // $this->row = app($this::$model);
        // $this->form = app(PanelFormService::class)->setPanel($this);
        $this->form = new PanelFormService($this);
        $this->route = $panelRouteService->setPanel($this);
    }

    public function areas(): Collection
    {
        return collect([]);
    }

    /*
    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    */

    /**
     * The relationships that should be eager loaded on index queries.
     */
    public function with(): array
    {
        return [];
    }

    public function setBuilder(Builder $query): self
    {
        $this->builder = $query;

        return $this;
    }

    /**
     * Undocumented function.
     * ret_old also |\Illuminate\Database\Query\Builder.
     *
     * @return Builder
     */
    public function getBuilder()
    {
        if ($this->builder instanceof Builder) {
            return $this->builder;
        }
        // 143    Call to an undefined method Illuminate\Database\Eloquent\Relations\Relation::with().
        // return $this->rows->with();
        // 145    Call to an undefined method Illuminate\Database\Eloquent\Relations\Relation::where().
        // return $this->rows->where('1=1');
        // return $this->rows->getQuery(); //Get the underlying query for the relation.

        $query = $this->getRows()->getQuery();
        if (! $query instanceof Builder) {
            throw new \Exception('['.__LINE__.']['.class_basename(self::class).']');
        }

        return $query;

        // return $this->rows->getBaseQuery();//Get the base query builder driving the Eloquent builder.
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        if (null !== $this->name) {
            return $this->name;
        } else {
            return $this->postType();
        }
    }

    /**
     * Undocumented function.
     *
     * @param Model $row
     */
    public function setRow($row): self
    {
        $this->row = $row;

        /*--- in teoria con la "&"
        $this->form->setPanel($this);
        $this->route->setPanel($this);
        */

        return $this;
    }

    // public function setRows(Builder $rows): self {
    /**
     * Undocumented function.
     *
     * @param Builder|Relation $rows
     */
    public function setRows($rows): self
    {
        $this->rows = $rows;

        return $this;
    }

    /*
     * get Row.
     *
     */
    public function getRow(): Model
    {
        if (! $this->row instanceof Model) {
            throw new \Exception('Row is null (route for container->item might be null)');
        }

        return $this->row;
    }

    /**
     * get Rows.
     *
     * @return Relation|Builder
     */
    public function getRows()
    {
        if (null === $this->rows) {
            // throw new \Exception('rows is null [line:'.__LINE__.'][class:'.get_class($this).']');
            // nel caso di stampare un pdf non serve avere le rows
        }

        /*
        if (null == $this->rows) {
            return $this->row->query();
        }
        */
        return $this->rows;
    }

    /* deprecated
    public function initRows(): self {
        $this->rows = $this->rows();
        //$this->rows = $this->rows; ??

        return $this;
    }
    */

    // Parameter #1 $panel of method Modules\Cms\Contracts\PanelContract::setParent()
    // expects Modules\Cms\Contracts\PanelContract,
    //        Modules\Cms\Contracts\PanelContract|null given.
    public function setParent(?PanelContract $panelContract): self
    {
        $this->parent = $panelContract;

        return $this;
    }

    public function setBrother(PanelContract $panelContract): self
    {
        $this->setParent($panelContract->getParent());
        $this->setName($panelContract->getName());

        return $this;
    }

    public function getSonModel(Model $model): PanelContract
    {
        $panelContract = PanelService::make()->get($model)->setParent($this);
        $name = Str::plural($panelContract->postType());
        $panelContract->setName($name);

        return $panelContract;
    }

    public function newPanel(Model $model): self
    {
        $cloned = clone $this;
        $cloned->setRow($model);
        $cloned->form->setPanel($cloned);
        $cloned->route->setPanel($cloned);

        return $cloned;
    }

    /**  se uso in rows() getQuery il dato ottenuto e' una collezione di items non di modelli.
     *
     */
    /* non usata ?
    public function getHydrate($data): PanelContract {
        if ('stdClass' == get_class($data)) {
            //$row = $this->row->hydrate((array) $data);
            $row = $this->row->forceFill((array) $data);
        } else {
            $row = $data;
        }
        $panel = PanelService::make()->get($row);
        $panel->setParent($this->getParent());

        return $panel;
    }
    */

    /**
     * getParent.
     */
    public function getParent(): ?PanelContract
    {
        return $this->parent;
    }

    /**
     * Undocumented function.
     *
     * @return Collection&iterable<PanelContract>
     */
    public function getParents()
    {
        $parents = collect([]);
        $panel_curr = $this->getParent();
        while ($panel_curr instanceof PanelContract) {
            $parents->prepend($panel_curr);
            $panel_curr = $panel_curr->getParent();
        }

        return $parents;
    }

    /**
     * @return Collection&iterable<PanelContract>
     */
    public function getBreads(): Collection
    {
        $class = class_basename($this);
        $check = ['_ModulePanel'/* 'HomePanel' */];

        $empty = [];
        if (\in_array($class, $check, true)) {
            return collect($empty);
        }
        if ($this->getParents()->count() > 0 && 'HomePanel' === $class) {
            return collect($empty);
        }
        $breads = $this->getParents();
        $breads->add($this);

        return $breads;
    }

    // questa funzione rilascia un array dei guid dell'url attuale
    /*
    public function getParentsGuid(): array {
        $parents = $this->getParents();
        $parent_first = $parents->first();
        // 300    Access to an undefined property object::$row.
        if (is_object($parent_first)) {
            while (null != $parent_first->row->parent) {
                $parent_first = PanelService::make()->get($parent_first->row->parent);
                $parents->prepend($parent_first);
            }
        }
        $parents_guid = $parents
            ->map(
                function ($item) {
                    return $item->row->guid;
                }
            )
            ->all();

        return $parents_guid;
    }
    */

    public function findParentType(string $type)
    {
        return collect($this->getParents())->filter(
            function ($item) use ($type): bool {
                // Cannot call method postType() on mixed.
                if (! $item instanceof PanelContract) {
                    return false;
                }

                return $type === $item->postType();
            }
        )->first();
    }

    /**
     * @return int|string|null
     */
    public function optionId(Model $model)
    {
        $id = $model->getKey();
        if (\is_int($id) || \is_string($id)) {
            return $id;
        }

        return null;
    }

    public function optionIdName(): string
    {
        return $this->row->getKeyName();
    }

    /**
     * on select the option label.
     */
    public function optionLabel(Model $model): string
    {
        // return $row->matr.' ['.$row->email.']['.$row->ha_diritto.'] '.$row->cognome.' '.$row->cognome.' ';
        return $model->getAttributeValue('title').''; // matr.' ['.$row->email.']['.$row->ha_diritto.'] '.$row->cognome.' '.$row->cognome.' ';
    }

    // public function optionLabelName():string {
    //    return 'matr';
    // }

    /*
     * ----
     */
    public function setInAdmin(?bool $in_admin): self
    {
        $this->in_admin = $in_admin;

        return $this;
    }

    public function getInAdmin(): ?bool
    {
        return $this->in_admin;
    }

    public function setRouteParams(array $route_params): void
    {
        $this->route_params = $route_params;
    }

    public function getRouteParams(): array
    {
        $route_current = Route::current();
        $route_params = \is_object($route_current) ? $route_current->parameters() : [];

        return array_merge($route_params, $this->route_params);
    }

    public function setItem(string $guid): self
    {
        $row = $this->row;
        // $rows = $this->getBuilder(); //builder espone il join per esteso percio' puo' perdere la ID
        $rows = $this->getRows();
        $tbl = $row->getTable();

        // 347    Method Illuminate\Database\Eloquent\Model::getRouteKeyName() invoked with 1 parameter, 0 required.
        // $pk = $row->getRouteKeyName($this->in_admin); //adesso restituisce guid, gli facciamo restituire "posts.guid" ?
        $pk = $row->getRouteKeyName(); // !!! MI SEMBRA STRANO !!
        $pk_full = $tbl.'.'.$pk;

        if ('guid' === $pk) {
            $pk_full = 'guid';
        } // pezza momentanea

        $value = Str::slug($guid); // retrocompatibilita'

        if ('guid' === $pk_full && method_exists($row, 'posts')) {
            // 301    Call to an undefined method Illuminate\Database\Eloquent\Builder|Illuminate\Database\Eloquent\Relations\Relation::whereHas().
            // if (! method_exists($rows, 'whereHas')) {
            //    throw new Exception('['.__LINE__.']['.class_basename(__CLASS__).']');
            // }
            /**
             * @var Builder
             */
            $query = $rows;
            // if ($rows instanceof Relation) {
            //    $query = $rows->getQuery();
            // }

            // if (! method_exists($query, 'whereHas')) {
            // throw new \Exception('['.__LINE__.']['.class_basename(__CLASS__).']');
            // }
            // Call to an undefined method Illuminate\Database\Eloquent\Builder|Illuminate\Database\Query\Builder::whereHas().
            $rows = $query->whereHas(
                'posts',
                function (Builder $query) use ($value): void {
                    $query->where('guid', $value);
                }
            );
        } else {
            $query = $rows;

            // ritorna il builder quindi è ri-possibile fare where
            if ($query instanceof Relation) {
                $query = $rows->getQuery();
            }

            $rows = $query->where([$pk_full => $value]);
        }
        $row = $rows->first();
        if ($row instanceof Model) {
            $this->row = $row;
        }

        return $this;
    }

    // funzione/flag da settare a true ad ogni pannello/modello che abbia le traduzioni (bandierina)
    public function hasLang(): bool
    {
        return false;
    }

    public function setLabel(string $label): Model
    {
        $model = $this->row;
        $res = $model::whereHas(
            'post',
            function (Builder $query) use ($label): void {
                $query->where('title', 'like', $label);
            }
        )->first();
        if (\is_object($res)) {
            return $res;
        }
        $me = $model->create();
        if (! method_exists($model, 'post')) {
            throw new \Exception('in ['.$model::class.'] method [post] is missing');
        }
        $post = $model->post()->create(
            [
                // 'post_id' => $me->getKey(),
                'title' => $label,
                'lang' => \App::getLocale(),
            ]
        );
        if (null === $post->post_id) {
            $post->post_id = $me->getKey();
            $post->save();
        }

        return $me;
    }

    public function title(): ?string
    {
        return $this->optionLabel($this->row);
    }

    public function txt(): ?string
    {
        // Access to protected property Illuminate\Database\Eloquent\Model
        // return $this->row->attributes['txt'];
        $txt = $this->row->getAttributeValue('txt');
        if (! \is_string($txt)) {
            return null;
        }

        return $txt;
    }

    public function optionsModelClass(string $model_class, array $where = [], array $where_in = []): array
    {
        $data = [];

        $row = app($model_class);
        $panel = PanelService::make()->get($row);
        $with = $panel->with();

        $rows = $model_class::with($with);
        if ([] != $where) {
            $rows = $rows->where($where);
        }
        if ([] != $where_in) {
            $key = array_keys($where_in)[0];
            $values = array_values($where_in)[0];

            // dddx([$key, $values]);
            $rows = $rows->whereIn($key, $values ?? []);
            // dddx(rowsToSql($rows));
        }
        $rows = $rows->get();

        foreach ($rows as $row) {
            $option_id = $panel->optionId($row);
            $option_label = $panel->optionLabel($row);
            $data[$option_id] = $option_label;
        }

        return $data;
    }

    public function optionsModelClassGrouped(string $model_class, string $group_by, array $where = []): array
    {
        $model = app($model_class);
        $panelContract = PanelService::make()->get($model);

        return $model->with($panelContract->with())
            ->where($where)
            ->get()
            ->groupBy($group_by)
            ->map(
                function ($item) use ($panelContract): array {
                    $data = [];
                    foreach ($item as $v) {
                        $option_id = $panelContract->optionId($v);
                        $option_label = $panelContract->optionLabel($v);
                        $data[$option_id] = $option_label;
                    }

                    return $data;
                }
            )
            ->all();
    }

    /**
     * @return array
     */
    public function optionsSelect()
    {
        $opts = [];
        $rows = $this->getBuilder()->get();

        foreach ($rows as $row) {
            $id = $this->optionId($row);
            $label = $this->optionLabel($row);

            $opts[$id] = $label;
        }

        return $opts;
    }

    /**
     * @param array|null $data
     */
    public function options($data = null)
    {
        if (null === $data) {
            $data = request()->all();
        }

        return $this->rows($data)->get();
    }

    public function optionsTree(array $data = []): array
    {
        /*
        if (null === $data  || empty($data) ) {
            $data = request()->all();
        }
        */

        // $test=\Modules\Blog\Models\Page::get()->toTree();
        // dddx($test);

        $rows = $this->getBuilder()->get();

        $keyName = $this->row->getKeyName();
        $chainService = new ChainService($keyName, 'parent_id', 'pos', $rows);

        return collect($chainService->chain_table)->map(
            function ($item): array {
                // Parameter #2 $multiplier of function str_repeat expects int, float|int given.
                $label = str_repeat('------', (int) $item->indent + 1).$this->optionLabel($item);

                return [
                    'id' => $this->optionId($item),
                    'label' => $label,
                ];
            }
        )->pluck('label', 'id')
            ->prepend('Root', 0)
            ->all();
    }

    /**
     * inserisco i campi dove si applicherà la funzione di ricerca testuale (solo nel pannello admin?)
     * se il pannello interessato rilascia un array vuoto, fa la ricerca tra i fillable del modello
     * esempio post.title, post.subtitle.
     */
    public function search(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function orderBy()
    {
        return [];
    }

    public function getOrderField()
    {
        return $this->row->getKeyName();
    }

    /**
     * Get the actions available for the resource.
     */
    public function fields(): array
    {
        return [];
    }

    public function getRules(?string $act = ''): array
    {
        return $this->rules($act);
    }

    public function rules(?string $act = ''): array
    {
        // $act = '';
        // extract($params);
        if ('' === $act) {
            $route_action = (string) \Route::currentRouteAction();
            $act = Str::after($route_action, '@');
        }
        $fields = match ($act) {
            'store' => $this->getFields('create'),
            'update' => $this->getFields('edit'),
            default => $this->fields(),
        };
        $act = request()->input('_act');
        if ('' !== $act) {
            $fields = collect($fields)->filter(
                function ($item) use ($act): bool {
                    if (! isset($item->except)) {
                        $item->except = [];
                    }

                    return ! \in_array($act, $item->except, true);
                }
            )->all();
        }

        foreach ($fields as $field) {
            if (\in_array($field->type, ['Cell', 'CellLabel'], true)) {
                foreach ($field->fields as $sub_field) {
                    $fields[] = $sub_field;
                }
            }
        }

        return collect($fields)->map(
            function ($item) {
                if (! isset($item->rules)) {
                    $item->rules = '';
                }
                /*
                if (Str::contains($item->type, 'RelationshipOne')) {
                    $rows_tmp = $this->row->{$item->name}();
                    if (method_exists($rows_tmp, 'getLocalKeyName')) {
                        $name1 = $rows_tmp->getLocalKeyName();
                    } else {
                        $name1 = $rows_tmp->getForeignKeyName();
                    }
                    $item->name = $name1;
                }
                */
                if ('pivot_rules' === $item->rules) {
                    $rel_name = $item->name;
                    /*
                    $pivot_class = with(new $this::$model())
                        ->$rel_name()
                        ->getPivotClass();
                    */
                    $pivot_class = app($this::$model)
                        ->$rel_name()
                        ->getPivotClass();

                    // $pivot = new $pivot_class();
                    $pivot = app($pivot_class());
                    $pivot_panel_name = StubService::make()->setModelAndName($pivot, 'panel')->get();
                    $pivot_panel = app($pivot_panel_name);
                    // $pivot_panel->setRows(with(new $this::$model())->$rel_name());
                    $pivot_panel->setRows(app($this::$model)->$rel_name());
                    $pivot_panel_rules = $pivot_panel->rules();

                    return collect($pivot_panel_rules)
                        ->map(
                            function ($pivot_rule_val, string $pivot_rule_key) use ($item): array {
                                $k = $item->name.'.*.pivot.'.$pivot_rule_key;

                                return [$k => $pivot_rule_val];
                            }
                        )->collapse()->all();
                }

                return [$item->name => $item->rules];
            }
        )->collapse()
            ->all();
    }

    public function pivotRules(array $params = []): array
    {
        extract($params);

        return [];
    }

    public function rulesMessages(): array
    {
        $lang = app()->getLocale();

        $fields = collect($this->fields())
            ->map(
                fn ($item) => (new FieldService())->setVars(get_object_vars($item))
            );

        $rules_msg_fields = $fields->filter(
            fn ($value, $key): bool => isset($value->rules_messages) && isset($value->rules_messages[$lang])
        )
            ->map(
                function ($item) use ($lang): array {
                    $tmp = [];

                    foreach ($item->rules_messages[$lang] as $k => $v) {
                        $tmp[$item->name.'.'.$k] = $v;
                    }

                    return $tmp;
                }
            )
            ->collapse()
            ->all();
        $mod = Str::before(Str::after(static::$model, 'Modules\\'), '\\');
        $mod = strtolower($mod);
        $name = Str::snake(class_basename(static::$model));
        $trans_ns = $mod.'::'.$name.'__rules_messages';
        // dddx($trans_ns);//food::restaurant_owner__rules_messages
        $rules_msg = trans($trans_ns);
        if (! \is_array($rules_msg)) {
            $rules_msg = [];
        }
        $rules_msg_generic = trans('ui::generic');
        if (! \is_array($rules_msg_generic)) {
            $rules_msg_generic = [];
        }
        $msg = [];
        // $msg = \array_merge($msg,$rules_msg_generic);
        // $msg = \array_merge($msg, $rules_msg);
        $msg = array_merge($msg, $rules_msg_fields);

        return $msg;
    }

    /**
     * Get the filters available for the resource.
     * Interagisce con la funzione applyFilter.
     */
    public function filters(Request $request = null): array
    {
        return [];
        /* esempio di filters da mettere nel pannello interessato
        return [
            (object) [
                'param_name' => 'stabi', //nome dell'input
                'field_name' => 'stabi', //nome del campo collegato
                'rules' => 'required',
                //'op'=>'=',
            ],
            (object) [
                'param_name' => 'repar',
                'field_name' => 'repar',
                'rules' => 'required',
                //'op'=>'=',
            ],
            (object) [
                'param_name' => 'year',
                'field_name' => 'anno',
                'rules' => 'required',
                //'where_method' => 'ofYear',
                //'op'=>'=',
            ],
        ];
        */
    }

    public function getXotModelName(): ?string
    {
        $models = config('morph_map');
        $res = collect($models)->search(static::$model);
        if (! \is_string($res)) {
            return null;
        }

        return $res;
    }

    /**
     * index Navigation view.
     * view utilizzata nell'index dei modelli (model/index) e nell'admin.
     *
     * @return null
     */
    public function indexNav(): ?Renderable
    {
        return null;
    }

    /**
     * Undocumented function.
     *
     * @return Collection<PanelContract>
     */
    public function getActions(?string $name)
    {
        return (new PanelActionService($this))->{__FUNCTION__}($name);
    }
    /*
    public function containerActions(array $params = []): Collection {
        return (new PanelActionService($this))->{__FUNCTION__}($params);
    }

    public function checkActions(array $params = []): Collection {
        return (new PanelActionService($this))->{__FUNCTION__}($params);
    }
    */
    /*
     * Undocumented function.
     *
     * @return Collection<XotBasePanelAction>

    public function itemActions(array $params = []): Collection {
        return (new PanelActionService($this))->{__FUNCTION__}($params);
    }
    */

    public function getAction(string $name): XotBasePanelAction
    {
        return (new PanelActionService($this))->{__FUNCTION__}($name);
    }

    /**
     * crea l'oggetto del pannello item (quello dove passi $row).
     */
    public function itemAction(string $act): XotBasePanelAction
    {
        return (new PanelActionService($this))->{__FUNCTION__}($act);
    }

    /**
     * crea l'oggetto del pannello Container (quello dove passi $rowS).
     */
    public function containerAction(string $act): XotBasePanelAction
    {
        return (new PanelActionService($this))->{__FUNCTION__}($act);
    }

    public function urlContainerAction(string $act, array $params = [])
    {
        return (new PanelActionService($this))->{__FUNCTION__}($act, $params);
    }

    public function urlItemAction(string $act, array $params = [])
    {
        return (new PanelActionService($this))->{__FUNCTION__}($act, $params);
    }

    public function btnItemAction(string $act, array $params = [])
    {
        return (new PanelActionService($this))->{__FUNCTION__}($act, $params);
    }

    // -- nella registrazione 1 tasto, nelle modifiche 3
    // public function btnSubmit() {
    // return Form::bsSubmit('save');
    //    return Form::bsSubmit(trans('xot::buttons.save'));
    // }

    /**
     * Build an "index" query for the given resource.
     * funzione richiamata prima di rilasciare rows nella blade.
     *
     * @param RowsContract $query
     *
     * @return RowsContract
     */
    public function indexQuery(array $data, $query)
    {
        // return $query->where('user_id', $request->user()->id);
        return $query;
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param RowsContract $query
     *
     * @return RowsContract
     */
    public static function relatableQuery(Request $request, $query)
    {
        // return $query->where('user_id', $request->user()->id);
        // return $query->where('user_id', $request->user()->id);
        return $query;
    }

    /* da rivalutare
    public function applyJoin(Builder $query): Builder {
        $model = $query->getModel();
        if (method_exists($model, 'scopeWithPost')) {
            $query = $query->withPost('a');
        }

        return $query;
    }
    */

    // |\Illuminate\Database\Query\Builder

    /**
     * Undocumented function.
     *
     * @param RowsContract $query
     *
     * @return RowsContract
     */
    public function applyFilter($query, array $filters)
    {
        return RowsService::filter($query, $filters, $this->filters());
    }

    /**
     * https://lyften.com/projects/laravel-repository/doc/searching.html.
     * https://spatie.be/docs/laravel-query-builder/v3/features/filtering
     * https://github.com/spatie/laravel-query-builder/issues/452.
     * https://forum.laravel-livewire.com/t/anybody-using-spatie-laravel-query-builder-with-livewire/299/5
     * https://github.com/spatie/laravel-query-builder/issues/243.
     * https://github.com/spatie/laravel-query-builder/pull/223.
     */

    /**
     * Undocumented function.
     *
     * @param RowsContract $query
     *
     * @return RowsContract
     */
    public function applySearch($query, ?string $q)
    {
        return RowsService::search($query, $q, $this->search());
    }

    /**
     * Undocumented function.
     *
     * @param RowsContract $query
     * @param mixed|null   $sort
     *
     * @return RowsContract
     */
    public function applySort($query, $sort = null)
    {
        if (! \is_array($sort)) {
            return $query;
        }
        // dddx([$query, $sort]);
        $column = $sort['by'] ?? '';
        /*
        * valutare se mettere controllo se colonna e' sortable
        **/
        if ('' === $column) {
            return $query;
        }
        $direction = $sort['order'] ?? 'asc';
        $tmp = explode('|', (string) $column);
        if (\count($tmp) > 1) {
            $column = $tmp[0];
            $direction = $tmp[1];
        }

        return $query->orderBy($column, $direction);
    }

    public function formCreate(array $params = [])
    {
        return $this->form->{__FUNCTION__}($params);
    }

    public function formEdit(array $params = [])
    {
        return $this->form->{__FUNCTION__}($params);
    }

    /**
     * ---.
     */
    public function formLivewireEdit(array $params = []): string
    {
        return $this->form->{__FUNCTION__}($params);
    }

    public function getFormData(array $params = [])
    {
        return $this->form->{__FUNCTION__}($params);
    }

    public function editObjFields(): array
    {
        return $this->form->{__FUNCTION__}();
    }

    /**
     * @return DataCollection<FieldData>
     */
    public function getFields(string $act): DataCollection
    {
        return $this->form->{__FUNCTION__}($act);
    }

    public function btnHtml(array $params): string
    {
        return $this->form->{__FUNCTION__}($params);
    }

    public function btnCrud(array $params = []): string
    {
        // return $this->form->{__FUNCTION__}($params);
        return (new PanelFormService($this))->{__FUNCTION__}();
    }

    public function imageHtml(array $params): string
    {
        // usare PanelImageService
        /*
        * mettere imageservice, o quello di spatie ?
        *
        **/
        $params['src'] = $this->row->image_src;
        $img = ImageService::make()->setVars($params);
        $src = $img->fit()->save()->src();
        if (! \is_string($src)) {
            throw new \Exception('src is not a string');
        }

        return '<img src="'.asset($src).'" >';
    }

    public function imgSrc(array $params): string
    {
        $params['dirname'] = '/photos/'.$this->postType().'/'.$this->guid();
        // Access to protected property Illuminate\Database\Eloquent\Model::$attribute
        // $params['src'] = $this->row->attributes['image_src'];
        $params['src'] = $this->row->getAttributeValue('image_src');
        $img = ImageService::make()->setVars($params);

        return $img->url();
    }

    public function microdataSchemaOrg(): string
    {
        return '';
    }

    public function show_ldJson(): array
    {
        return [];
    }

    public function relatedUrl(string $name, string $act = 'index'): string
    {
        return $this->route->{__FUNCTION__}($name, $act);
    }

    public function langUrl(string $lang): string
    {
        return $this->route->{__FUNCTION__}(['lang' => $lang]);
    }

    public function actionUrl(string $name, array $params = []): string
    {
        $xotBasePanelAction = $this->getAction($name);
        if (! is_object($xotBasePanelAction)) {
            return '#';
        }

        return $xotBasePanelAction->url();
    }

    public function url(string $act = 'show', array $params = []): string
    {
        $url = $this->route->{__FUNCTION__}($act);

        if ([] !== $params) {
            $url_components = parse_url((string) $url);

            if (! isset($url_components['path'])) {
                throw new \Exception('['.__LINE__.']['.__FILE__.']');
            }
            $url = $url_components['path'];

            $merged = $params;
            if (isset($url_components['query'])) {
                parse_str($url_components['query'], $originalParams);

                $merged = array_replace_recursive($originalParams, $params);
            }

            $merged = $this->deleteLivewireIndexesFromUrl($merged);

            $url .= '?'.Arr::query($merged);
            /*if (strpos($url, 'fingerprint') !== false) {
                dd($merged);
            }*/
        }

        return $url;
    }

    public function deleteLivewireIndexesFromUrl(array $params): array
    {
        // Delete livewire params from url
        recursive_unset($params, 'fingerprint');
        recursive_unset($params, 'serverMemo');
        recursive_unset($params, 'children');
        recursive_unset($params, 'updates');
        recursive_unset($params, 'type');
        recursive_unset($params, 'payload');
        recursive_unset($params, 'method');
        recursive_unset($params, 'params');

        return $params;
    }

    public function relatedName(string $name, int $id = null): PanelContract
    {
        // -- il name e' il nome della relazione ..
        /*
        $model = xotModel($name);
        if (null != $id) {
            $model = $model->find($id);
        }
        $panel = PanelService::make()->get($model);
        $panel = $panel->setParent($this);

        return $panel;
        */

        $name = Str::camel($name); // nome relazioni sono per convenzione in camel case
        $related = $this->row->{$name}()->getRelated();
        $relatedPanel = PanelService::make()->get($related);
        $relatedPanel->setName($name);
        $relatedPanel->setParent($this);

        return $relatedPanel;
    }

    public function postType(): string
    {
        $models = config('morph_map');
        $post_type = collect($models)->search($this->row::class);
        if (false === $post_type) {
            $post_type = snake_case(class_basename($this->row));
        }

        return (string) $post_type;
    }

    /**
     * Undocumented function.
     */
    public function guid(bool $is_admin = null): ?string
    {
        if (isset($is_admin) && $is_admin) {
            $id = $this->row->getKey();
            if (! \is_int($id) && ! \is_string($id)) {
                throw new \Exception('['.__LINE__.']['.__FILE__.']');
            }

            return (string) $id;
        }/*
        if (null !== $this->getInAdmin() && $this->getInAdmin()) {
            return (string) $this->row->getKey();
        }
        */
        if (inAdmin()) {
            $id = $this->row->getKey();
            if (! \is_int($id) && ! \is_string($id) && null !== $id) {
                throw new \Exception('['.__LINE__.']['.__FILE__.'] - '.$id);
            }

            return (string) $id;
        }
        $row = $this->row;
        $key = $row->getRouteKeyName();
        // if (! in_array($key, $row->getFillable())) {
        //    throw new \Exception('field ['.$key.'] not set in model ['.class_basename($row).'] fillable ');
        // }

        if (null === $row->getKey()) {
            return null;
        }
        // 1049   Dead catch - Exception is never thrown in the try block.
        // try {
        $guid = $row->$key;
        // } catch (\Exception $e) {
        //     $guid = '';
        // }
        if ('' === $guid && method_exists($row, 'post') && 'guid' === $key) {
            // if ('' == $row->id && '' != $row->post_id) {
            //    $row->id = $row->post_id; //finche netson non riabilita migrazioni
            // }
            // 1059   Dead catch - Exception is never thrown in the try block.
            // try {
            return $row->post->guid;
            // } catch (\Exception $e) {
            //                $title = $this->postType().' '.$this->row->getKey();

            //    $post = $row->post()->firstOrCreate(
            //        [
            //            'lang' => app()->getLocale(),
            //        ],
            //        [
            //            'title' => $title,
            //            'guid' => Str::slug($title),
            //        ]
            //    );

            //    return $post->guid;
            // }
        }

        return (string) $guid;
    }

    /*
    public function getTitle() {
       $name = $this->getName();
       //$title = str_replace('_', ' ', $title);
       $row = $this->panel->getRow();

       $module_name_low = strtolower(getModuleNameFromModel($row));
       $title = trans($module_name_low.'::'.strtolower(class_basename($row)).'.act.'.$name);

       return $title;
    }
    */

    public function getCrudActions(): array
    {
        $acts = ['index', 'create'];
        /* indexedit in via di deprecazione
        if (
            //1095   Cannot call method getName() on object|string.

            Str::endsWith(
                request()
                    ->route()
                    ->getName(),
                'index_edit',
            )
        ) {
            $acts = ['indexEdit', 'create'];
        }
        */
        /*
        $route_params = getRouteParameters();
        $panel = PanelService::make()->getByParams($route_params);
        */
        // dddx(PanelService::make()->getRequestPanel()->row->getKey());
        // dddx($this->row->getKey());
        if ($this->row->getKey()) {
            $acts[] = 'edit';
        }

        $trad_mod = $this->getTradMod();
        $actions = [];
        foreach ($acts as $act) {
            $url = $this->url($act);
            $url1 = Str::before($url, '?');
            $req_path = '/'.request()->path();
            $active = $url1 === $req_path;

            $tmp = new \stdClass();
            $tmp->title = trans($trad_mod.'.tab.'.$act);
            $tmp->url = $url;
            $tmp->act = $act;
            $tmp->active = $active;

            if (Gate::allows($act, $this)) {
                $actions[] = $tmp;
            }
        }

        return $actions;
    }

    public function getTradMod(): string
    {
        $modulenameLow = $this->getModulenameLow();

        return $modulenameLow.'::'.Str::snake($this->getName());
    }

    public function getItemTabs(): DataCollection
    {
        return (new PanelTabService($this))->{__FUNCTION__}();
    }

    public function getRowTabs(): array
    {
        return (new PanelTabService($this))->{__FUNCTION__}();
    }

    public function getTabs(): array
    {
        return (new PanelTabService($this))->{__FUNCTION__}();
    }

    // Return value of Modules\Cms\Models\Panels\XotBasePanelService::rows()
    // must be an instance of Illuminate\Database\Eloquent\Builder,
    // instance of Illuminate\Database\Eloquent\Relations\MorphToMany returned

    /*
    public function rowsTest(?array $data = null) {
        $query = $this->getRows();
        $test = app(Pipeline::class)
        ->send($query)
        ->through([
            //'App\QueryFilters\LikeMatch:search,title,excerpt,summary',
            'Modules\Xot\QueryFilters\Search',
        ])
        ->thenReturn()
        ->get();
        dddx(['query' => $query, 'test' => $test]);
    }
    */

    /**
     * init + filter + search + sort => ??? trovare nome.
     *
     * @return RowsContract
     */
    public function rows(array $data = null)
    {
        if (null === $data) {
            $data = request()->all();
        }

        $filters = $data;
        $q = $data['q'] ?? null;
        $sort = $data['sort'] ?? null;
        $query = $this->getRows();
        // $query = $this->getBuilder();
        if (null == $query) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
            // 1404   Method Modules\Cms\Models\Panels\XotBasePanel::rows() should return Modules\Cms\Contracts\RowsContract but returns null.
            // return null; // ????
        }

        $with = $this->with();
        if (method_exists($query, 'with')) {
            $query = $query->with($with);
        }
        // https://laravelvuejs.com/query-filters-in-laravel-70cafa5d4b64
        /*
        $test=app(Pipeline::class)
            ->send($query)
            ->through([
                'App\QueryFilters\LikeMatch:search,title,excerpt,summary',
            ])
            ->thenReturn()
            ->paginate();
        */

        $query = $this->indexQuery($data, $query);

        $query = $this->applyFilter($query, $filters);

        $query = $this->applySearch($query, $q);

        if (! Route::is('*edit*')) {
            $query = $this->applySort($query, $sort);
        }

        /*
        $query = QueryBuilder::for($query)
            ->allowedFilters($this->row->getFillable())
            ->allowedSorts($this->row->getFillable());
        */

        return $query;
    }

    /**
     * elastic restituisce gia' i dati paginati.
     */
    public function rowsPaginated(): LengthAwarePaginator
    {
        return $this->rows()->paginate(20);
    }

    public function getFillable(): array
    {
        return $this->row->getFillable();
    }

    public function callItemActionWithGate(string $act)
    {
        // $actions = $this->actions();
        // dddx([get_class($this), $actions]);
        $method_act = Str::camel($act);
        $authorized = Gate::allows($method_act, $this);

        if (! $authorized) {
            return $this->notAuthorized($method_act);
        }

        return $this->callItemAction($act);
    }

    /**
     * @return RedirectResponse|Response
     */
    public function notAuthorized(string $method)
    {
        $class = PolicyService::get($this)->createIfNotExists()->getClass();

        $lang = app()->getLocale();
        if (! \Auth::check()) {
            $referer = \Request::path();

            return to_route('login', ['lang' => $lang, 'referer' => $referer])
                ->withErrors(['active' => 'login before']);
        }

        $msg = 'Auth Id ['.\Auth::id().'] not can ['.$method.'] on ['.$class.']';

        if (! view()->exists('pub_theme::errors.403')) {
            $msg = '<h3> Aggiungere la view : pub_theme::errors.403<br/>pub_theme: '.config('xra.pub_theme').'</h3>';
            throw new \Exception($msg);
        }

        return response()->view('pub_theme::errors.403', ['message' => $msg], 403);
    }

    public function callAction(string $act)
    {
        if (null == $this->row) {
            abort(404);
        }

        $xotBasePanelAction = $this->getAction($act);

        if (! \is_object($xotBasePanelAction)) {
            $msg = 'action '.$act.' not recognized for ['.static::class.']';
            FileService::viewCopy('ui::errors.403', 'pub_theme::errors.403');

            return response()->view('pub_theme::errors.403', ['message' => $msg], 403);
        }

        $xotBasePanelAction->setRow($this->row);
        $rows = $this->rows();
        $xotBasePanelAction->setRows($rows);

        $xotBasePanelAction->setPanel($this);

        $method = request()->getMethod();
        if ('GET' === $method) {
            return $xotBasePanelAction->handle();
        }

        return $xotBasePanelAction->postHandle();
    }

    public function callItemAction(string $act)
    {
        // Strict comparison using === between null and string will always evaluate to false
        // if (null === $act) {
        //    return null;
        // }
        // $action = $this->getActions('item')
        //    ->firstWhere('name', $act);
        $xotBasePanelAction = $this->itemAction($act);

        if (! \is_object($xotBasePanelAction)) {
            $msg = '<h3>['.$act.'] not exists in ['.static::class.']</h3>Items Actions Avaible are :';
            foreach ($this->getActions('item') as $act) {
                $msg .= '<br/>'.$act->getName();
            }

            return $msg;
        }
        $xotBasePanelAction->setRow($this->row);
        $xotBasePanelAction->setPanel($this);
        $method = request()->getMethod();

        return 'GET' === $method ? $xotBasePanelAction->handle() : $xotBasePanelAction->postHandle();
    }

    public function callContainerAction(string $act)
    {
        // Strict comparison using === between null and string will always evaluate to false
        // if (null === $act) {
        //    return null;
        // }
        // $action = $this->getActions('container')
        //    ->firstWhere('name', $act);
        $xotBasePanelAction = $this->containerAction($act);

        if (! \is_object($xotBasePanelAction)) {
            abort(403, 'action '.$act.' not recognized');
        }

        $data = request()->all();
        $rows = $this->rows($data);
        $xotBasePanelAction->setRows($rows);
        $xotBasePanelAction->setPanel($this);
        $method = request()->getMethod();

        return 'GET' === $method ? $xotBasePanelAction->handle() : $xotBasePanelAction->postHandle();
    }

    public function out(array $params = [])
    {
        try {
            return $this->presenter->out();
        } catch (\Exception $e) {
            /**
             * @phpstan-var view-string
             */
            $view = 'pub_theme::errors.500';
            if (! view()->exists($view)) {
                FileService::viewCopy('ui::errors.500', 'pub_theme::errors.500');
            }

            return response()->view($view, ['message' => $e->getMessage()], 500);
        }
    }

    /*//--- valutare
    public function __toString() {
        return $this->presenter->out()->render();
    }
    */

    public function pdfFilename(array $params = []): string
    {
        $fields = ['matr', 'cognome', 'nome', 'anno'];
        extract($params);
        $filename_arr = [];
        $filename_arr[] = $this->postType();
        $filename_arr[] = $this->guid();
        foreach ($fields as $field) {
            if (property_exists($this->row, 'field') && $this->row->$field !== null) {
                $filename_arr[] = $this->row->$field;
            }
        }
        $filename_arr[] = date('Ymd');
        $filename = implode('_', $filename_arr);
        if (request()->input('debug')) {
            $filename .= '.html';
        } else {
            $filename .= '.pdf';
        }

        return $filename;
    }

    /**
     * Undocumented function.
     */
    public function xls(array $params = [])
    {
        $xlsPanelPresenter = new XlsPanelPresenter();
        $xlsPanelPresenter->setPanel($this);

        return $xlsPanelPresenter->out($params);
    }

    /**
     * Undocumented function.
     */
    public function pdf(array $params = []): string
    {
        $pdfPanelPresenter = new PdfPanelPresenter();
        $pdfPanelPresenter->setPanel($this);

        $pdfPanelPresenter->setViewParams($params['view_params'] ?? []);

        return $pdfPanelPresenter->out($params);
    }

    // Method Modules\Cms\Models\Panels\XotBasePanelService::related() should return Modules\Cms\Models\Panels\XotBasePanel but returns Modules\Cms\Contracts\PanelContract|null.
    public function related(string $relationship): PanelContract
    {
        $related = $this->row->$relationship()->getRelated();
        $panelContract = PanelService::make()->get($related);
        $panelContract->setParent($this);

        return $panelContract;
    }

    public function getModuleName(): string
    {
        $str = static::class;
        // $str = $this::$model;
        $module_name = Str::before(Str::after($str, 'Modules\\'), '\\Models\\');

        return $module_name;
    }

    public function getModuleNameLow(): string
    {
        return Str::lower($this->getModuleName());
    }

    public function breadcrumbs(): array
    {
        return [];
        /*
        $curr = $this;
        $parents = [];
        while (null != $curr) {
            $parents[] = $curr;
            $curr = $curr->getParent();
        }
        $bread = [];
        $tmp = (object) [];
        $tmp->url = asset(app()->getLocale());
        $tmp->title = 'Home';
        $tmp->obj = \Theme::xotModel('home');
        $tmp->method = 'index';
        $bread[] = $tmp;
        foreach ($parents as $parent) {
            $tmp = (object) [];
            $tmp->url = $parent->url('index');
            $tmp->title = $parent->postType();
            $tmp->obj = \Theme::xotModel($tmp->title);
            $tmp->method = 'index';
            $bread[] = $tmp;
            try {
                $tmp = (object) [];
                $tmp->url = $parent->url('show');
                $tmp->title = $parent->getRow()->title;
                $tmp->obj = \Theme::xotModel($parent->postType());
                $tmp->method = 'show';
                $bread[] = $tmp;
            } catch (\exception $e) {
            }
        }
        //dddx($bread);

        return $bread;
        */
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public function getExcerpt($length = 225)
    {
        $row = $this->row;

        $content = $row->getAttributeValue('subtitle') ?: $row->getAttributeValue('txt');

        // [2022-05-20 00:22:19] local.ERROR: preg_replace():
        // Argument #3 ($subject) must be of type array|string, null given (View: /home/cvfcmxwn/laraxot/multi/laravel/Themes/DirectoryBs4/Resources/views/layouts/widgets/blog_items.blade.php) {"view":{"view":"/home/cvfcmxwn/laraxot/multi/laravel/Modules/Cms/Models/Panels/XotBasePanel.php","data":[]},"
        // url":"http://prosecco-valdobbiadene.it/?page=9","

        if (null === $content) {
            $content = '';
        }

        if (! \is_string($content)) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }

        // 1737   Parameter #1 $str of function strip_tags expects string, array|string|null given.
        $tmp = preg_replace(['/<pre>[\w\W]*?<\/pre>/', '/<h\d>[\w\W]*?<\/h\d>/'], '', $content);
        // Call to function is_array() with string|null will always evaluate to false
        // if (\is_array($tmp)) {
        //    $tmp = implode(' ', $tmp);
        // }
        if (null === $tmp) {
            $tmp = '';
        }
        $cleaned = strip_tags($tmp, '<code>');
        $truncated = substr($cleaned, 0, $length);

        if (substr_count($truncated, '<code>') > substr_count($truncated, '</code>')) {
            $truncated .= '</code>';
        }

        return \strlen($cleaned) > $length
            ? preg_replace('/\s+?(\S+)?$/', '', $truncated).'...'
            : $cleaned;
    }

    /**
     * @return array
     */
    public function indexEditSubs()
    {
        return [];
    }

    /**
     * @return string
     */
    public function swiperItem()
    {
        return 'pub_theme::layouts.swiper.item';
    }

    public function view(array $params = null)
    {
        return $this->presenter->out($params);
    }

    /**
     * under costruction,.
     */
    public function getViews(): array
    {
        $views = [];

        $views[] = $this->getView();
        $act = RouteService::getAct();
        $view = $this->getModuleNameLow().'::'.(inAdmin() ? 'admin.' : '').$this->getName().'.'.$act;
        $views[] = $this->getViewWithFormat($view);
        $view = (inAdmin() ? 'adm_theme' : 'pub_theme').'::layouts.default.'.$act;
        $views[] = $this->getViewWithFormat($view);
        /**
         * @phpstan-var view-string
         */
        $view = 'ui::layouts.default'.(inAdmin() ? '.admin' : '').'.'.$act;
        $views[] = $this->getViewWithFormat($view);

        return $views;
    }

    public function getView(): string
    {
        $params = getRouteParameters();
        $view1 = RouteService::getView().'.'.RouteService::getAct();
        $view1 = Str::replace('..', '.', $view1);

        if (inAdmin()) {
            $mod = $params['module'] ?? RouteService::getModuleName();
            $view1 = strtolower((string) ($mod.'::'.$view1));
        } else {
            $view1 = 'pub_theme::'.$view1;
        }
        // dddx(['view0' => $view, 'view1' => $view1, 'route_action' => $route_action]);
        $view1 = Str::replace('::.', '::', $view1);
        // dddx(['view1' => $view1, 'view2' => $view2]);

        return $this->getViewWithFormat($view1);
    }

    public function getViewWithFormat(string $view): string
    {
        $act = request('_act', '');
        $act = Str::snake($act);
        if ('' !== $act) {
            $view .= '.acts.'.$act;
        }

        return $view;
    }

    public function getViewWork(array $params = []): string
    {
        $views = $this->getViews();

        $view_work = collect($views)->first(
            fn ($view_check) => view()->exists($view_check)
        );
        if (false === $view_work || null === $view_work) {
            $ddd_msg =
                [
                    'err' => 'Not Exists ..',
                    'pub_theme' => config('xra.pub_theme'),
                    'adm_theme' => config('xra.adm_theme'),
                    'view0_dir' => FileService::viewNamespaceToDir($views[0]),
                    'views' => $views,
                    // 'debug_backtrace' => debug_backtrace(),
                ];

            dddx($ddd_msg);
        }

        return $view_work;
    }

    public function id(bool $is_admin = null): string
    {
        $curr = $this;
        $data = collect([]);
        while ($curr instanceof PanelContract) {
            // $data->prepend($curr->postType().'-'.$curr->guid($is_admin));
            $data->prepend($curr->postType().'-'.$curr->getRow()->getKey());

            $curr = $curr->getParent();
        }

        return $data->implode('-');
    }

    public function getPath(bool $is_admin = null): string
    {
        $curr = $this;
        $data = collect([]);
        while ($curr instanceof PanelContract) {
            $data->prepend($curr->postType());

            $curr = $curr->getParent();
        }

        return $data->implode('-');
    }

    /**
     * Get the tabs available.
     */
    public function tabs(): array
    {
        return [];
    }

    /**
     * Get the actions available for the resouce.
     */
    public function actions(): array
    {
        return [];
    }

    /**
     * ---.
     */
    public function update(array $data): PanelContract
    {
        // $func = '\Modules\Xot\Jobs\Crud\\'.Str::studly(__FUNCTION__).'Job';
        // $func = '\Modules\Xot\Jobs\PanelCrud\\'.Str::studly(__FUNCTION__).'Job';

        // $panel = $func::dispatchNow($data, $this);
        $panel = app(UpdateAction::class)->execute($this, $data);

        return $panel;
    }

    public function isRevisionBy(UserContract $userContract): bool
    {
        $model = $this->getRow();

        return $model->getAttributeValue('created_by') === $userContract->handle
        || $model->getAttributeValue('updated_by') === $userContract->handle
        || $model->getAttributeValue('user_id') === $userContract->id;
    }

    public function isAuthoredBy(UserContract $userContract): bool
    {
        $model = $this->getRow();
        if (null === $model->author) {
            return false;
        }

        // return $row->author->is($user);
        return $model->author_id === $userContract->id;
    }

    /**
     * ----------------------- WIP -----------------------.
     */
    public function isModeratedBy(UserContract $userContract): bool
    {
        $model = $this->getRow();
        if (null === $model->author) {
            return false;
        }

        // return $row->author->is($user);
        return $model->author_id === $userContract->id;
    }

    /**
     * ----------------------- WIP -----------------------.
     */
    public function isAdminedBy(UserContract $userContract): bool
    {
        $model = $this->getRow();
        if (null === $model->author) {
            return false;
        }

        // return $row->author->is($user);
        return $model->author_id === $userContract->id;
    }

    /* -- si fa con action non coi job
    public function store(array $data) {
        $func = StoreJob::class;
        $panel = $func::dispatch($data, $this);

        return $panel;
    }
    */
    /*
    public function actionModals() {
        return [];
    }

    public function itemActionModals() {
        $action_modals = collect($this->actionModals())->filter(function ($action) {
            if (isset($action->onItem) && true === $action->onItem) {
                return $action;
            }
        });

        $action_modals = $action_modals->map(
function ($action) {
            $class_name = \get_class($action);
            $class_name = Str::after($class_name, 'Livewire\\');
            $class_arr = \explode('\\', $class_name);
            $class_arr = collect($class_arr)->map(
function ($parz_path) {
                return \lcfirst($parz_path);
            })->toArray();
            $class_name = \implode('.', $class_arr);
            $class_name = Str::snake($class_name, '-');

            return [
                'class' => $class_name,
                'icon' => $action->icon,
            ];
        });

        $allowed_action_modals = $action_modals->filter(function ($action) {
            $path_arr = \explode('.', $action['class']);
            $act = Str::camel($path_arr[count($path_arr) - 1]);
            if (Gate::allows($act, $this)) {
                return $action;
            }
        });

        return $allowed_action_modals;
    }
    */
}
