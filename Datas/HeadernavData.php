<?php

declare(strict_types=1);

namespace Modules\Cms\Datas;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;
use Livewire\Wireable;
use Modules\Tenant\Services\TenantService;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;

class HeadernavData extends Data implements Wireable
{
    use WireableData;

    public ?string $background_color = null;

    public ?string $background = null;

    public ?string $overlay_color = null;

    public ?int $overlay_opacity = null;

    public ?string $class = null;

    public ?string $style = null;

    /**
     * @var view-string
     */
    public string $view;

    private static ?self $instance = null;

    public static function make(): self
    {
        if (! self::$instance instanceof HeadernavData) {
            // if (! self::$instance) {
            $data = TenantService::getConfig('appearance');
            $data = Arr::get($data, 'headernav', []);
            self::$instance = self::from($data);
        }

        return self::$instance;
    }

    public function view(): Renderable
    {
        $view_params = $this->toArray();
        if (! view()->exists($this->view)) {
            $message = 'The view ['.$this->view.'] does not exist';
            throw new \Exception($message);
        }

        return view($this->view, $view_params);
    }
}
