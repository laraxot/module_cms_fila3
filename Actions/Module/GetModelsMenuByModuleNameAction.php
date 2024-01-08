<?php

declare(strict_types=1);

namespace Modules\Cms\Actions\Module;

use Modules\Cms\Datas\NavbarMenuData;
use Modules\Cms\Services\PanelService;
use Spatie\LaravelData\DataCollection;
use Spatie\QueueableAction\QueueableAction;

final class GetModelsMenuByModuleNameAction
{
    use QueueableAction;

    /**
     * Undocumented function.
     *
     * @return DataCollection<NavbarMenuData>
     */
    public function execute(string $module_name): DataCollection
    {
        $models = app(GetModelsByModuleNameAction::class)->execute($module_name);
        $menu = collect($models)->map(
            function ($item, $key): array {
                // $obj = new $item();
                $obj = app($item);
                $panelContract = PanelService::make()->get($obj);
                if ($key === 'media') {// media e' singolare ma anche plurale di medium
                    $panelContract->setName('medias');
                }

                $url = $panelContract->url('index');

                return [
                    'title' => $key,
                    'url' => $url,
                    'active' => false,
                ];
            }
        );

        return NavbarMenuData::collection($menu->all());
    }
}
