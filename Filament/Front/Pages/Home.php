<?php
/**
 * @see https://laraveldaily.com/post/filament-custom-edit-profile-page-multiple-forms-full-design
 */

declare(strict_types=1);

namespace Modules\Cms\Filament\Front\Pages;

use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Home extends Page // implements HasTable
{// use InteractsWithTable;
                                                                            // use InteractsWithForms;

                                                                            protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // protected static string $view = 'cms::filament.front.pages.welcome';
    protected static string $view = 'pub_theme::home';

    protected static string $layout = 'pub_theme::layouts.app';

    public string $view_type;

    public array $containers = [];

    public array $items = [];

    public function mount()
    {
        [$this->containers,$this->items] = params2ContainerItem();
        $this->initView();
    }

    public function getViewData(): array
    {
        $data = [];
        if (\count($this->containers) > 0) {
            $container_last = last($this->containers);
            $item_last = last($this->items);

            $container_last_singular = Str::singular($container_last);
            $container_last_model = \Illuminate\Database\Eloquent\Relations\Relation::getMorphedModel($container_last_singular);
            if (null === $container_last_model) {
                dddx('insert model inside the morph_map');
            }
            $container_last_key_name = app($container_last_model)->getRouteKeyName();

            $row = $container_last_model::where($container_last_key_name, $item_last)
                ->first();
            $data[$container_last_singular] = $row;
        }

        return $data;
    }

    public function mountOLD(string $lang = null,
        string $container0 = null, string $item0 = null,
        string $container1 = null, string $item1 = null,
        string $container2 = null, string $item2 = null,
        string $container3 = null, string $item3 = null
    ) {
        $containers = [];
        $items = [];
        for ($i = 0; $i < 4; ++$i) {
            if ($container_curr = ${'container'.$i}) {
                $containers[$i] = $container_curr;
            }
            if ($item_curr = ${'item'.$i}) {
                $items[$i] = $item_curr;
            }
        }
    }

    public function initView(): void
    {
        $containers = $this->containers;
        $items = $this->items;
        $view = '';
        if (\count($containers) === \count($items)) {
            $view = 'show';
        }
        if (\count($containers) > \count($items)) {
            $view = 'index';
        }
        if (0 === \count($containers)) {
            $view = 'home';
        }

        $this->view_type = $view;

        $views = [];

        if (\count($containers) > 0) {
            $views[] = 'pub_theme::'.implode('.', $containers).'.'.$view;

            $model_root = Str::singular($containers[0]);
            $res = \Illuminate\Database\Eloquent\Relations\Relation::getMorphedModel($model_root);
            $module_name = Str::between($res, 'Modules\\', '\Models\\');
            $module_name_low = Str::lower($module_name);
            $views[] = $module_name_low.'::'.implode('.', $containers).'.'.$view;
        } else {
            $views[] = 'pub_theme::'.$view;
        }

        $view_work = Arr::first($views, static function (string $view) {
            return view()->exists($view);
        });

        self::$view = $view_work;
    }

    public function url(string $name, array $parameters = []): string
    {
        $parameters['lang'] = app()->getLocale();
        $record = $parameters['record'];
        if ('show' === $name) {
            $container0 = class_basename($record);
            $container0 = Str::plural($container0);
            $container0 = Str::snake($container0);
            $parameters['container0'] = $container0;
            $parameters['item0'] = $record;

            return route('test', $parameters);
        }
        if ('index' === $name) {
            $container0 = class_basename($record);
            $container0 = Str::plural($container0);
            $container0 = Str::snake($container0);
            $parameters['container0'] = $container0;

            return route('test', $parameters);
        }
        $parameters['container0'] = 'articles';
        $parameters['item0'] = 'zibibbo';

        return route('test', $parameters);
    }
}
