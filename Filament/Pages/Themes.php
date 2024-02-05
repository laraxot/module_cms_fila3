<?php

declare(strict_types=1);
/**
 * @see https://github.com/3x1io/filament-themes/blob/main/src/Pages/Themes.php
 */

namespace Modules\Cms\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\File;
use Modules\Cms\Datas\ThemeData;
use Modules\Tenant\Services\TenantService;

class Themes extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static string $view = 'cms::filament.pages.themes';

    protected static ?string $navigationGroup = 'Settings';

    public array $data = [];

    /*
    public static function getNavigationGroup(): ?string
    {
        return config('filament-themes.group') ?? static::$navigationGroup;
    }
    */

    /**
     * @return array[]
     */
    protected function getViewData(): array
    {
        $themes = File::directories(base_path().(string) str('/Themes')->replace('/', \DIRECTORY_SEPARATOR));
        $data = [];
        if ($themes) {
            foreach ($themes as $key => $item) {
                $filename = $item.\DIRECTORY_SEPARATOR.'theme.json';
                if (! File::exists($filename)) {
                    $theme_data = ThemeData::from(['name' => basename($item)]);
                    File::put($filename, $theme_data->toJson());
                }
                $info = json_decode(File::get($filename));

                $info->image = '#';

                $data[] = [
                    'id' => $key + 1,
                    'path' => $item,
                    'info' => $info,
                ];
            }
        }
        $this->data = $data;

        return compact('data');
    }

    public function changePubTheme(string $name)
    {
        $data['pub_theme'] = $name;
        TenantService::saveConfig('xra', $data);
        Notification::make()
        ->title('Saved successfully')
        ->success()
        ->send();
        // dddx(config('xra.pub_theme'));
    }

    /*
    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('page_Themes');
    }


    public function mount(): void
    {
        abort_unless(auth()->user()->can('page_Themes'), 403);
    }
    */
}
