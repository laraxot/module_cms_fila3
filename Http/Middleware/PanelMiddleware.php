<?php

declare(strict_types=1);

namespace Modules\Cms\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Cms\Services\PanelService;

/**
 * Class PanelMiddleware.
 */
class PanelMiddleware
{
    /**
     * @return Response|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $route_params = getRouteParameters();
        try {
            $panel = PanelService::make()
                ->getByParams($route_params);
        } catch (Exception $exception) {
            return response()
                ->view('pub_theme::errors.404', ['message' => $exception->getMessage(), 'lang' => 'it'], 404);
        }

        PanelService::make()->setRequestPanel($panel);

        return $next($request);
    }
}
