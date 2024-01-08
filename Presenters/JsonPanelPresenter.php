<?php

declare(strict_types=1);

namespace Modules\Cms\Presenters;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Collection;
use Modules\Cms\Contracts\PanelContract;
use Modules\Cms\Contracts\PanelPresenterContract;
use Modules\Xot\Services\StubService;

/**
 * Class JsonPanelPresenter.
 */
class JsonPanelPresenter implements PanelPresenterContract
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
    }

    /**
     * @return mixed|void
     *
     * @throws FileNotFoundException
     * @throws \ReflectionException
     */
    public function outContainer(?array $params = null)
    {
        $model = $this->panel->getRow();
        $transformer = StubService::make()->setModelAndName($model, 'transformer_collection')->get();
        $lengthAwarePaginator = $this->panel->rows()->paginate(20);

        return new $transformer($lengthAwarePaginator);
    }

    /**
     * @return mixed|void
     *
     * @throws FileNotFoundException
     * @throws \ReflectionException
     */
    public function outItem(?array $params = null)
    {
        $model = $this->panel->getRow();
        $transformer = StubService::make()->setModelAndName($model, 'transformer_resource')->get();

        return new $transformer($model);
    }

    /**
     * @return mixed|void
     */
    public function out(?array $params = null)
    {
        if (isContainer()) {
            return $this->outContainer($params);
        }

        return $this->outItem($params);
    }
}
