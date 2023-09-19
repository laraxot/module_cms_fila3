<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Button;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Modules\Cms\Actions\GetConfigKeyByViewAction;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelContract;
use Modules\Xot\View\Components\XotBaseComponent;

use function Safe\json_encode;

/**
 * Class Panel.
 */
class Panel extends XotBaseComponent
{
    public array $attrs = [];
    public string $icon;
    public string $view;

    /**
     * Undocumented function.
     */
    public function __construct(public PanelContract $panelContract, public string $tpl = 'v1', public string $type = 'create')
    {
        // $this->view = app(GetViewAction::class)->execute($type.'.'.$this->tpl);
        $this->view = app(GetViewAction::class)->execute($this->tpl);
        $this->attrs['class'] = app(GetConfigKeyByViewAction::class)->execute($this->view, $type.'.class');
        // dddx([$this->attrs, $this->view]);
        $this->attrs['href'] = $panelContract->url($type);
        $this->attrs['title'] = $type;
        $this->attrs['data-toggle'] = 'tooltip';
        // $this->icon = trans($panel->getTradMod().'.'.$type);
        $this->icon = app(GetConfigKeyByViewAction::class)->execute($this->view, $type.'.icon');

        if ('delete' == $type) {
            // tacconamento di emergenza!
            // $this->view = 'ui::components.button.delete.v2';
            $model = $this->panelContract->getRow();

            $model_type = Str::snake(class_basename($model));
            $parz = json_encode(['model_id' => $model->getKey(), 'model_type' => $model_type], JSON_HEX_APOS);
            if (false === $parz) {
                throw new Exception('['.__LINE__.']['.__FILE__.']');
            }
            $parz = str_replace('"', "'", $parz);

            $onclick = "Livewire.emit('modal.open', 'modal.panel.destroy',".$parz.')';
            $this->attrs['onclick'] = $onclick;
        }
    }

    public function render(): Renderable
    {
        /**
         * @phpstan-var view-string
         */
        $view = $this->view;

        $view_params = [
            'view' => $view,
        ];

        return view($view, $view_params);
    }

    public function shouldRender(): bool
    {
        if ('detach' == $this->type && ! isset($this->panelContract->getRow()->pivot)) {
            return false;
        }

        return Gate::allows($this->type, $this->panelContract);
    }
}
