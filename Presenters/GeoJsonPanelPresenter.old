<?php

declare(strict_types=1);

namespace Modules\Cms\Presenters;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Collection;
use Modules\Cms\Contracts\PanelContract;
use Modules\Cms\Contracts\PanelPresenterContract;
use Modules\Cms\Services\PanelService;
use Modules\Xot\Transformers\GeoJsonCollection;
use Modules\Xot\Transformers\GeoJsonResource;

/**
 * Class GeoJsonPanelPresenter.
 */
class GeoJsonPanelPresenter implements PanelPresenterContract
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
     * @throws FileNotFoundException
     * @throws \ReflectionException
     */
    public function outContainer(?array $params = null): GeoJsonCollection
    {
        $model = $this->panel->getRow();
        $model->getTable();
        PanelService::make()->get($model)->postType();
        $transformer = GeoJsonCollection::class;
        app()->getLocale();
        $rows = $this->panel->rows();
        /*
        $post_table = app(Post::class)->getTable();
        $rows = $rows->join($post_table.' as post',
            function ($join) use ($lang,$model_table,$model_type) {
                $join->on('post.post_id', '=', $model_table.'.id')
                    ->select('title', 'guid', 'subtitle')
                    ->where('lang', $lang)
                    ->where('post.post_type', $model_type)
                ;
            }
            )
                    ->select('post.post_id', 'post_type', 'guid', 'latitude', 'longitude')
                    ->where('latitude', '!=', '')
                   // ->where('lang', $lang)
                    ->paginate(100)
                    ->appends(\Request::input());
        */
        $rows = $rows->paginate(100);
        // --------

        return new $transformer($rows);
    }

    public function outItem(?array $params = null): GeoJsonResource
    {
        $model = $this->panel->getRow();
        $transformer = GeoJsonResource::class;

        return new $transformer($model);
    }

    public function out(?array $params = null): GeoJsonCollection|GeoJsonResource
    {
        if (isContainer()) {
            return $this->outContainer($params);
        }

        return $this->outItem($params);
    }
}
