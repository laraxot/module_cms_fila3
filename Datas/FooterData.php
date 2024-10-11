<?php

declare(strict_types=1);

namespace Modules\Cms\Datas;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;
use Livewire\Wireable;
use Modules\Tenant\Services\TenantService;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;

class FooterData extends Data implements Wireable
{
    use WireableData;

    /**
     * @var view-string
     */
    public string $view;

    private static ?self $instance = null;

    public static function make(): self
    {
        if (! self::$instance instanceof FooterData) {
            $data = TenantService::getConfig('appearance');
            $data = Arr::get($data, 'footer', []);
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
