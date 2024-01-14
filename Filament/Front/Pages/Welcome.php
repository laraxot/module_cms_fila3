<?php
/**
 * @see https://laraveldaily.com/post/filament-custom-edit-profile-page-multiple-forms-full-design
 */

declare(strict_types=1);

namespace Modules\Cms\Filament\Front\Pages;

use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Tenant\Services\TenantService;
use Webmozart\Assert\Assert;

// implements HasTable
class Welcome extends Page
{
    // use InteractsWithTable;
    // use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // protected static string $view = 'cms::filament.front.pages.welcome';
    protected static string $view = 'pub_theme::home';

    protected static string $layout = 'pub_theme::layouts.app';

    public string $view_type;

    public array $containers = [];

    public array $items = [];

    public ?Model $model = null;

    public function mount(): void
    {
        [$this->containers,$this->items] = params2ContainerItem();
        $this->initView();
    }

    public function getViewData(): array
    {
        $data = [];
        if (\count($this->containers) > 0) {
            Assert::string($container_last = last($this->containers));
            $item_last = last($this->items);

            $container_last_singular = Str::singular($container_last);

            $container_last_model = TenantService::model($container_last_singular);

            $container_last_key_name = $container_last_model->getRouteKeyName();

            $row = $container_last_model::firstWhere([$container_last_key_name => $item_last]);

            $data[$container_last_singular] = $row;

            if (null === $row) {
                abort(404);
            }
        }

        return $data;
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
            Assert::string($model_class = TenantService::modelClass($containers[0]));
            $module_name = Str::between($model_class, 'Modules\\', '\Models\\');
            $module_name_low = Str::lower($module_name);
            $views[] = $module_name_low.'::'.implode('.', $containers).'.'.$view;
        } else {
            $views[] = 'pub_theme::'.$view;
        }

        Assert::string($view_work = Arr::first(
            $views,
            static function (string $view) {
                return view()->exists($view);
            }
        ));

        if (null === $view_work) {
            dddx($views);
        }

        self::$view = $view_work;
    }

    public function url(string $name = 'show', array $parameters = []): string
    {
        // dddx($parameters);
        $parameters['lang'] = app()->getLocale();
        $record = $parameters['record'] ?? $this->model;
        // dddx($record);
        if ('show' === $name) {
            $container0 = class_basename($record);
            $container0 = Str::plural($container0);
            $container0 = Str::snake($container0);
            $parameters['container0'] = $container0;
            $parameters['item0'] = $record->slug;

            // dddx($parameters);
            // unset($parameters['record']); // per togliere quel ?record=n dall'url, che non dovrebbe servire?
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

    public function setModel(Model $model): self
    {
        $this->model = $model;

        return $this;
    }
}
