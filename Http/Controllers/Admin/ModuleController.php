<?php

declare(strict_types=1);

namespace Modules\Cms\Http\Controllers\Admin;

use Modules\Cms\Contracts\PanelContract;
use Exception;
use Illuminate\Http\Request;
use Modules\Cms\Http\Controllers\BaseController;
use Modules\Cms\Services\PanelService;

class ModuleController extends BaseController
{
    /**
     * ---.
     *
     * @return mixed|void
     */
    public function index(Request $request)
    {
        $panelContract = PanelService::make()->getRequestPanel();
        if (!$panelContract instanceof PanelContract) {
            throw new Exception('['.__LINE__.']['.__FILE__.']');
        }
        $act = $request->_act;
        if ('' !== $act && $panelContract instanceof PanelContract) {
            // return $panel->callItemActionWithGate($request->_act);
            // return $panel->callContainerAction($request->_act);
            return $panelContract->callAction($act);
        }

        return $panelContract->out();
    }

    /**
     * ---.
     *
     * @return mixed|void
     */
    public function store(Request $request)
    {
        return $this->index($request);
    }

    /**
     * ---.
     *
     * @return mixed|void
     */
    public function home(Request $request)
    {
        $panelContract = PanelService::make()->getRequestPanel();
        if (!$panelContract instanceof PanelContract) {
            throw new Exception('['.__LINE__.']['.__FILE__.']');
        }
        $act = $request->input('_act', '');
        if ('' !== $act) {
            if (! \is_string($act)) {
                throw new Exception('['.__LINE__.']['.class_basename(self::class).']');
            }

            return $panelContract->callItemActionWithGate($act);
            // return $panel->callContainerAction($request->_act);
            // return $panel->callAction($request->_act);
        }

        return $panelContract->out();
    }

    /**
     * ---.
     *
     * @return mixed|void
     */
    public function dashboard(Request $request)
    {
        $panelContract = PanelService::make()->getRequestPanel();
        if (!$panelContract instanceof PanelContract) {
            throw new Exception('['.__LINE__.']['.__FILE__.']');
        }
        $act = $request->input('_act', '');
        if ('' !== $act) {
            if (! \is_string($act)) {
                throw new Exception('['.__LINE__.']['.class_basename(self::class).']');
            }

            return $panelContract->callItemActionWithGate($act);
            // return $panel->callContainerAction($request->_act);
            // return $panel->callAction($request->_act);
        }

        return $panelContract->out();
    }
}
// */
