<?php

declare(strict_types=1);

namespace Modules\Cms\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Providers\XotBaseServiceProvider;
use Modules\Xot\Services\FileService;
use Modules\Xot\Services\LivewireService;
use Webmozart\Assert\Assert;

/**
 * Undocumented class.
 */
class CmsServiceProvider extends XotBaseServiceProvider
{
    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public string $module_name = 'cms';

    public XotData $xot;

    public function bootCallback(): void
    {
        $this->xot = XotData::make();

        if ($this->xot->register_pub_theme) {
            $this->registerNamespaces('pub_theme');

            $this->registerThemeConfig('pub_theme');
            $this->registerThemeLivewireComponents();
        }

        Assert::string($timezone = config('app.timezone') ?? 'Europe/Berlin');

        date_default_timezone_set($timezone);
    }

    public function registerCallback(): void
    {
        $this->xot = XotData::make();
        $configFileName = 'xra';
        $this->mergeConfigFrom(__DIR__.sprintf('/../Config/%s.php', $configFileName), $configFileName);

        if ($this->xot->register_pub_theme) {
            Assert::isArray($paths = config('view.paths'));
            $theme_path = FileService::fixPath(base_path('Themes/'.$this->xot->pub_theme.'/Resources/views'));
            $paths = array_merge([$theme_path], $paths);
            Config::set('view.paths', $paths);

            //\Laravel\Folio\Folio::path($theme_path.'/foliopages');
            //\Livewire\Volt\Volt::mount($theme_path.'/foliopages');
        }
    }

    /**
     * Undocumented function.
     */
    public function registerThemeLivewireComponents(): void
    {
        // $prefix=$this->module_name.'::';
        $prefix = '';
        LivewireService::registerComponents(
            base_path('Themes/'.$this->xot->pub_theme.'/Http/Livewire'),
            'Themes\\'.$this->xot->pub_theme,
            $prefix,
        );
    }

    /**
     * Undocumented function.
     */
    public function registerNamespaces(string $theme_type): void
    {
        $xot = $this->xot;

        $theme = $xot->{$theme_type};

        $resource_path = 'Themes/'.$theme.'/Resources';
        $lang_dir = FileService::fixPath(base_path($resource_path.'/lang'));

        $theme_dir = FileService::fixPath(base_path($resource_path.'/views'));

        app('view')->addNamespace($theme_type, $theme_dir);
        $this->loadTranslationsFrom($lang_dir, $theme_type);
    }

    public function registerThemeConfig(string $theme_type): void
    {
        $xot = $this->xot;

        $theme = $xot->{$theme_type};

        $config_path = base_path('Themes/'.$theme.'/Config');
        if (! File::exists($config_path)) {
            return;
        }

        $files = File::files($config_path);
        foreach ($files as $file) {
            $name = $file->getFilenameWithoutExtension();
            $real_path = $file->getRealPath();
            if (false === $real_path) {
                throw new \Exception('['.__LINE__.']['.class_basename(self::class).']');
            }

            $data = File::getRequire($real_path);
            Config::set($theme_type.'::'.$name, $data);
        }

        // ---------------------
    }
}
