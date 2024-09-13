<?php

declare(strict_types=1);

namespace Modules\Cms\Filament\Clusters\Appearance\Pages;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Cms\Filament\Clusters\Appearance;
use Modules\Tenant\Services\TenantService;
use Modules\UI\Filament\Forms\Components\RadioImage;
use Modules\Xot\Actions\View\GetViewsSiblingsAndSelfAction;
use Webmozart\Assert\Assert;

/**
 * @property Forms\ComponentContainer $form
 */
class Headernav extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'cms::filament.clusters.appearance.pages.headernav';

    protected static ?string $cluster = Appearance::class;

    protected static ?int $navigationSort = 1;

    public function mount(): void
    {
        $this->fillForms();
    }

    public function form(Form $form): Form
    {
        $view = 'cms::components.headernav.simple';
        $view_p = Str::beforeLast($view, '.');
        $views = app(GetViewsSiblingsAndSelfAction::class)->execute($view);

        $options = Arr::mapWithKeys($views, function (string $item) use ($view_p) {
            $k = $view_p.'.'.$item;

            return [$k => $k];
        });

        /*
        $options = Arr::map($views, function ($view) {
            return app(\Modules\Xot\Actions\File\AssetAction::class)->execute('ui::img/headernav/screenshots/'.$view.'.png');
        });
        */
        return $form
            ->schema([
                ColorPicker::make('background_color'),
                FileUpload::make('background'),
                ColorPicker::make('overlay_color'),
                TextInput::make('overlay_opacity')->numeric()->minValue(0)->maxValue(100),
                TextInput::make('class'),
                TextInput::make('style'),
                /*
                RadioImage::make('_tpl')
                ->label('layout')
                ->options($options)
                ->columnSpanFull(),
                */
                Select::make('view')
                    ->label('view')
                    ->options($options),
            ])->columns(2)
            ->statePath('data');
    }

    public function updateData(): void
    {
        try {
            $data = $this->form->getState();
            $up = [
                'headernav' => $data,
            ];
            TenantService::saveConfig('appearance', $up);
        } catch (Halt $exception) {
            Notification::make()
                ->title('Error!')
                ->danger()
                ->body($exception->getMessage())
                ->persistent()
                ->send();

            return;
        }

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }

    protected function fillForms(): void
    {
        Assert::isArray($data = TenantService::config('appearance'));
        Assert::isArray($data = Arr::get($data, 'headernav', []));

        $this->form->fill($data);
    }

    protected function getUpdateFormActions(): array
    {
        return [
            Action::make('updateAction')
                ->label(__('filament-panels::pages/auth/edit-profile.form.actions.save.label'))
                ->submit('editForm'),
        ];
    }
}
