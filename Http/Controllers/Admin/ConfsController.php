<?php

declare(strict_types=1);

namespace Modules\Cms\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Cms\Http\Controllers\BaseController;
use Modules\Cms\Models\Conf;
use Modules\Cms\Services\PanelService;
use Modules\Tenant\Services\TenantService;

/**
 * Class ConfController.
 */
class ConfsController extends BaseController
{
    /**
     * Undocumented function.
     */
    public function index(Request $request): View
    {
        // $rows = TenantService::getConfigNames();
        $panelContract = PanelService::make()->getRequestPanel();
        if ($panelContract == null) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }

        return $panelContract->out();
    }

    /**
     * Undocumented function.
     *
     * @return Renderable|string
     */
    public function edit(Request $request)
    {
        $request->all();
        $route_params = getRouteParameters();
        [$containers,$items] = params2ContainerItem($route_params);
        $conf_id = last($items); // google
        $rows = app(Conf::class)->getRows();
        $row = collect($rows)->firstWhere('id', $conf_id);
        $conf_name = $row['name'];

        $name = TenantService::getName();
        Str::replace('/', '.', $name.'/'.$conf_name);
        $filename = TenantService::filePath($conf_name.'.php');

        /*
        dddx([
            'conf_name' => $conf_name,
            'name' => $name,
            'config_key' => $config_key,
            'test1' => config($config_key),
            'filename' => $filename,
        ]);

        return 'preso';
        // */
        $view = 'ui::admin.standalone.manage.php-array';
        $view_params = [
            'view' => $view,
            'filename' => $filename,
        ];

        return view($view, $view_params);
    }
}
