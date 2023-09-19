<?php

declare(strict_types=1);
use Modules\Cms\Http\Middleware\PanelMiddleware;

use Illuminate\Support\Facades\Route;
use Modules\Cms\Services\RouteService;
use Modules\Xot\Datas\XotData;

$acts = [
    (object) [
        'name' => 'create',
        'methods' => ['get', 'head'],
        'uri' => '/create',
    ],
    (object) [
        'name' => 'edit',
        'methods' => ['get', 'head'],
        'uri' => '/edit',
    ],
    (object) [
        'name' => 'index_edit',
        'methods' => ['get', 'head'],
        'uri' => '/index_edit',
    ],
    (object) [
        'name' => 'attach',
        'methods' => ['get', 'head', 'post', 'put'],
        'uri' => '/attach',
    ],
    (object) [
        'name' => 'detach',
        'methods' => ['get', 'head'],
        'uri' => '/detach',
    ],
    (object) [
        'name' => 'store',
        'methods' => ['post'],
        'uri' => '',
    ],
    (object) [
        'name' => 'update',
        'methods' => ['put', 'patch'],
        'uri' => '',
    ],
    (object) [
        'name' => 'index',
        'methods' => ['get', 'head'],
        'uri' => '',
        // corretto che sia diverso da name,
        'uri_full' => '/{container0?}/{item0?}/{container1?}/{item1?}/{container2?}/{item2?}/{container3?}/{item3?}/{container4?}',
    ],
    /*(object) [
        'name' => 'home',
        'methods' => ['get', 'head'],
        'uri' => '',
        //corretto che sia diverso da name,
        'uri_full' => '',
    ],
    */
    (object) [
        'name' => 'show',
        'methods' => ['get', 'head'],
        'uri' => '',
    ],
    (object) [
        'name' => 'destroy',
        'methods' => ['delete'],
        'uri' => '',
    ],
];

$xot = XotData::make();

$name = '/{container0?}/{item0?}/{container1?}/{item1?}/{container2?}/{item2?}/{container3?}/{item3?}/{container4?}/{item4?}';

$controller = 'ContainersController';

$front_acts = collect($acts)->filter(
    fn($item): bool => in_array($item->name, ['index', 'show'], true)
)->all();

$middleware = [
    'web',
    PanelMiddleware::class,
];
$namespace = '\Modules\Cms\Http\Controllers';
$prefix = '/{lang?}';
$as = ''; // null
if (! $xot->disable_frontend_dynamic_route) {
    Route::middleware($middleware)
        ->namespace($namespace)
        ->group(
            function () use ($controller): void {
                Route::get('/', $controller.'@home')->name('home');
                Route::get('/home', $controller.'@home')->name('wellcome');
            }
        );

    RouteService::myRoutes($name, $middleware, $namespace, $prefix, $as, $controller, $front_acts);
}

$middleware = [
    'web',
    'auth',
    // 'verified',
    PanelMiddleware::class,
];

if ($xot->login_verified) {
    $middleware[] = 'verified';
}

$namespace = '\Modules\Cms\Http\Controllers\Admin';

$prefix = '/admin/{module?}/{lang?}';
$as = 'admin.';

if (! $xot->disable_admin_dynamic_route) {
    RouteService::myRoutes($name, $middleware, $namespace, $prefix, $as, $controller, $acts);
}

/*
 * Undocumented function.
 *
 * @return void
 */
