<?php

declare(strict_types=1);

namespace Modules\Cms\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Http\Controllers\BaseController;
use Modules\Settings\Services\ConfService;
use Modules\Tenant\Services\TenantService;
use Modules\UI\Services\ThemeService;

/**
 * Class ConfController.
 */
class ConfController extends BaseController
{
    public function index(Request $request): View
    {
        $route_params = getRouteParameters();
        // $confs = Config::all('localhost');
        $name = TenantService::getName();
        $confs = Config::get($name);
        $rows = collect($confs)->map(
            function ($item, $key) use ($route_params) {
                $route_params['item0'] = $key;

                return (object) [
                    'title' => $key,
                    'url' => route('admin.containers.edit', $route_params, false),
                ];
            }
        )->all();

        /*
        return ThemeService::v1iew()
                ->with('rows', $rows)
                // ->with('row',$row)
        ;
        */
        $view = app(GetViewAction::class)->execute();
        $view_params = [
            'rows' => $rows,
            // 'row'=>$row,
        ];

        return view($view, $view_params);
    }

    public function edit(Request $request): Renderable
    {
        $route_params = getRouteParameters();
        extract($route_params);
        if (! isset($item0)) {
            dddx(['err' => 'item0 is missing']);
            throw new Exception('item0 is missing');
            // return;
        }
        $row = config($item0);

        // return ThemeService::v1iew()->with('row', $row);
        $view = app(GetViewAction::class)->execute();
        $view_params = [
            'row' => $row,
        ];

        return view($view, $view_params);
    }

    /**
     * @return RedirectResponse|void
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $route_params = getRouteParameters();
        collect($data)->except(['_token', '_method'])->all();

        extract($route_params);
        if (! isset($item0)) {
            dddx(['err' => 'item0 is missing']);

            return;
        }
        throw new Exception('['.__LINE__.']['.class_basename(self::class).']');
        // TenantService::saveConfig($item0, $data);
        /*
        $data['_token'] = '';
        unset($data['_token']);
        $data['_method'] = '';
        unset($data['_method']);
        $res = ConfService::set([
            'name' => $item0,
            'data' => $data,
            //'msg'=>'['.$config_file.']!',
        ]);
        $msg = 'Aggiornato ['.$res['filename'].'!';
        \Session::flash('status', $msg.' '.\Carbon\Carbon::now());

        return redirect()->back();
        */
        // $msg = 'aggiornato';
        // \Session::flash('status', $msg.' '.\Carbon\Carbon::now());

        // return redirect()->back();
    }
}
