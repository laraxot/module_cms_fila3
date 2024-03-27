<?php

declare(strict_types=1);

namespace Modules\Cms\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Modules\Cms\Contracts\PanelContract;
use Modules\Cms\Http\Controllers\BaseController;
use Modules\Cms\Http\Requests\XotRequest;
use Modules\Cms\Services\PanelService;
use Modules\Xot\Services\FileService;
use Modules\Xot\Services\PolicyService;

// ---- services ---

/**
 * Undocumented class.
 *
 * @method Renderable home(Request $request)
 * @method Renderable show(Request $request)
 */
class ContainersController extends BaseController
{
    public PanelContract $panel;

    /**
     * Undocumented function.
     *
     * @return mixed|null
     */
    public function index(Request $request)
    {
        $route_params = getRouteParameters(); // "module" => "lu"
        [$containers,$items] = params2ContainerItem();
        // dddx(['contianers' => $containers, 'items' => $items]);
        if ([] === $containers) {
            $act = isset($route_params['module']) ? 'home' : 'dashboard';

            return $this->{$act}($request);
        }

        if (\count($containers) === \count($items)) {
            return $this->show($request);
        }

        return $this->__call('index', $route_params);
    }

    /**
     * Undocumented function.
     *
     * @param string $method
     * @param array  $args
     */
    public function __call($method, $args)
    {
        // dddx([$method, $args]);
        $route_current = \Route::current();
        if ($route_current instanceof \Illuminate\Routing\Route) {
            $action = $route_current->getAction();
            $action['controller'] = self::class.'@'.$method;
            $action = $route_current->setAction($action);
        }

        $panel = PanelService::make()->getRequestPanel();

        if (! $panel instanceof PanelContract) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }

        $this->panel = $panel;
        if ('' !== request()->input('_act', '')) {
            return $this->__callPanelAct($method, $args);
        }

        return $this->callRouteAct($method, $args);
    }

    public function callRouteAct(string $method, array $args)
    {
        $panel = $this->panel;

        $authorized = Gate::allows($method, $panel);

        if (! $authorized) {
            return $this->notAuthorized($method, $panel);
        }

        $request = XotRequest::capture();
        $controller = $this->getController();

        return app($controller)->$method($request, $panel);
    }

    public function __callPanelAct(string $method, array $args)
    {
        $request = request();
        $act = $request->_act;
        $method_act = Str::camel($act);

        $panel = $this->panel;

        $authorized = Gate::allows($method_act, $panel);
        if (! $authorized) {
            return $this->notAuthorized($method_act, $panel);
        }

        return $panel->callAction($act);
    }

    /**
     * @return JsonResponse|RedirectResponse|Response
     */
    public function notAuthorized(string $method, PanelContract $panelContract)
    {
        $lang = app()->getLocale();

        if (! Auth::check()) {
            $referer = \Request::path();

            return to_route('login', ['lang' => $lang, 'referer' => $referer])
                ->withErrors(['active' => 'login before']);
        }

        $class = PolicyService::get($panelContract)->createIfNotExists()->getClass();
        $msg = 'Auth Id ['.Auth::id().'] not can ['.$method.'] on ['.$class.']';
        FileService::viewCopy('ui::errors.403', 'pub_theme::errors.403');

        return response()->view('pub_theme::errors.403', ['msg' => $msg], 403);
    }

    /**
     * Undocumented function.
     */
    public function getController(): string
    {
        [$containers, $items] = params2ContainerItem();
        $mod_name = $this->panel->getModuleName(); // forse da mettere container0

        $tmp = collect($containers)->map(
            static fn ($item) => Str::studly($item)
        )->implode('\\');
        if ('' === $tmp) {
            $tmp = 'Module';
        }

        $controller = '\Modules\\'.$mod_name.'\Http\Controllers\Admin\\'.$tmp.'Controller';

        if (class_exists($controller)) {
            return $controller;
        }

        if ('Module' === $tmp) {
            // return '\Modules\Cms\Http\Controllers\Admin\ModuleController';
            return '\\'.ModuleController::class;
        }

        // return '\Modules\Cms\Http\Controllers\Admin\XotPanelController';
        return '\\'.XotPanelController::class;
    }
}
