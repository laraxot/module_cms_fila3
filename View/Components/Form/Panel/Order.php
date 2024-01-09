<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Form\Panel;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Request;
use Illuminate\View\Component;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Services\PanelService;

class Order extends Component
{
    public array $qs;

    public array $options;

    public array $options1 = ['desc' => 'desc', 'asc' => 'asc'];

    public array $input_attrs = [];

    public array $form_attrs = ['method' => 'get'];

    public ?string $sort_by;

    public ?string $sort_order;

    public function __construct(public string $tpl = 'v1')
    {
        $panelContract = PanelService::make()->getRequestPanel();

        $query = request()->query();
        $this->qs = collect($query)
<<<<<<< HEAD
            ->except(['sort'])
            ->all();
=======
                    ->except(['sort'])
                    ->all();
>>>>>>> dev
        if (! is_null($panelContract)) {
            $this->options = array_combine($panelContract->orderBy(), $panelContract->orderBy());
        } else {
            throw new \Exception('['.__LINE__.']['.__FILE__.'], panel is null');
        }

        $this->input_attrs = ['placeholder' => 'Ordinamento', 'label' => ' '];
        if ('inline' === $tpl) {
            $this->form_attrs['class'] = 'd-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search float-right';
        }

        $sort_by = Request::input('sort.by');

        $sort_order = Request::input('sort.order');

        $this->sort_by = $sort_by;
        $this->sort_order = $sort_order;
    }

    // public function setSortOrderAttributes():array{
    //     return [
    //         'options' = ['desc' => 'desc', 'asc' => 'asc'],
    //         'attrs' = ['placeholder' => 'Ordinamento', 'label' => ' '],

    //     ];
    // }

    public function render(): Renderable
    {
        /**
         * @phpstan-var view-string
         */
        $view = app(GetViewAction::class)->execute($this->tpl);

        $view_params = [];

        return view($view, $view_params);
    }
}
