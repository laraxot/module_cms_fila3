<?php

declare(strict_types=1);

namespace Modules\Cms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class XotPanelController.
 */
class XotPanelController extends BaseController
{
    /**
     * @param string $method
     * @param array  $arg
     */
    public function __call($method, $arg)
    {
        $act = '\Modules\Cms\Actions\Panel\\'.Str::studly($method).'Action';
        $data = $arg[0];
        if ($arg[0] instanceof Request) {
            $data = $data->all();
        }

        $panel = app($act)->execute($arg[1], $data);

        return $panel->out();
    }
}
