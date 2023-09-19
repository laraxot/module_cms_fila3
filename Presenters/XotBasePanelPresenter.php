<?php

declare(strict_types=1);

namespace Modules\Cms\Presenters;

use Illuminate\Support\Collection;
use Modules\Cms\Contracts\PanelContract;
use Modules\Cms\Contracts\PanelPresenterContract;

abstract class XotBasePanelPresenter implements PanelPresenterContract
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
     */
    public function out(array $params = null): void
    {
    }
}
