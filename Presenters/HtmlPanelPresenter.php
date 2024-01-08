<?php

declare(strict_types=1);

namespace Modules\Cms\Presenters;

use App;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Modules\Cms\Contracts\PanelContract;
use Modules\Cms\Contracts\PanelPresenterContract;
use Modules\Xot\Services\FileService;

/**
 * Class HtmlPanelPresenter.
 */
class HtmlPanelPresenter implements PanelPresenterContract
{
    protected PanelContract $panel;

    public function setPanel(PanelContract &$panelContract): self
    {
        $this->panel = $panelContract;

        return $this;
    }

    /**
     * @return mixed|void
     */
    public function index(?Collection $collection): void
    {
        /*
        $count = $items->count();
        $last_update = $items
            ->sortByDesc
            ->created_at
            ->first()
            ->created_at
            ->format('d/m/Y');

        $data = [
            'Numero elementi' => $count,
            'Ultimo aggiornamento' => $last_update,
        ];

        return view()->make('workshop::index')->with(compact('items', 'data'));
        */
    }

    // eturn \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response|string

    public function out(?array $params = null): Renderable
    {
        [$containers, $items] = params2ContainerItem();
        $view = $this->panel->getView(); // vew che dovrebbe essere
        $view_work = $this->panel->getViewWork(); // view effettiva
        $views = $this->panel->getViews(); // views possibili

        $mod_trad = $this->panel->getTradMod();

        View::composer(
            '*',
            function ($view_params) use ($view): void {
                View::share('view', $view);
                $trad = implode('.', \array_slice(explode('.', (string) $view), 0, -1));
                View::share('trad', $trad);
                View::share('lang', \App::getLocale());
                View::share('_panel', $this->panel);
                // \View::share('mod_trad', $mod_trad);

                // $rows = $this->panel->rows()->paginate(20); //se lo metto lo esegue un fracasso di volte
                // View::share('rows', $rows);
            }
        );

        $modal = null;
        if (\Request::ajax()) {
            $modal = 'ajax';
        } elseif (\Request::input('format') === 'iframe') {
            $modal = 'iframe';
        }

        $rows_err = '';
        // $rows = $this->panel->rows()->paginate(20);

        $rows = $this->panel->rowsPaginated();

        // dddx(['rows'=>$rows,'scout'=>Press::search('war')->get()]);

        $route_params = [];
        $route_name = '';
        $route_current = \Route::current();
        if ($route_current !== null) {
            $route_params = $route_current->parameters();
            $route_name = $route_current->getName();
        }

        // dddx($route_current->urlAct());

        $view_params = [
            'view' => $view,
            'view_work' => $view_work,
            'views' => $views,
            '_panel' => $this->panel,
            '_panel_name' => $this->panel->getName(),
            'row' => $this->panel->getRow(),
            'rows' => $rows,
            'rows_err' => $rows_err,
            'mod_trad' => $mod_trad,
            'trad_mod' => $mod_trad, // / da sostiutire ed uccidere
            'params' => $route_params,
            'routename' => $route_name,
            'modal' => $modal,
            'containers' => $containers,
            'items' => $items,
            // 'page' => new \Modules\UI\Services\Objects\PageObject(),
        ];

        /*
        $pieces = [
            'layouts.app',
            'layouts.plane',
            'layouts.partials.htmlheader',
            'layouts.partials.headernav',
            'layouts.partials.footer',
            'layouts.partials.scripts',
            'auth.links',
            'layouts.partials.headernav.lang',
            'layouts.partials.modal',
        ];
        foreach ($pieces as $piece) {
            FileService::viewCopy('ui::'.$piece, 'pub_theme::'.$piece);
        }
        */
        // if (null === $view_work) {
        //    throw new Exception(' ['.implode(' , '.chr(13).chr(10), $views).'] one of these must exists pub_theme: ['.config('xra.pub_theme').']');
        // }

        return view($view_work, $view_params); // ->render(); //se metto render , non mi prende piu' i parametri passati con with
    }
}
