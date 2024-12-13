<?php

declare(strict_types=1);

namespace Modules\Cms\Filament\Clusters\Appearance\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Webmozart\Assert\Assert;
use Filament\Forms\Components\Select;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Modules\Tenant\Services\TenantService;
use Modules\Cms\Filament\Clusters\Appearance;
use Filament\Forms\Concerns\InteractsWithForms;
use Modules\UI\Filament\Forms\Components\RadioImage;
use Modules\Xot\Actions\View\GetViewsSiblingsAndSelfAction;
use Modules\Xot\Actions\Filament\Block\GetViewBlocksOptionsByTypeAction;

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
        $options = app(GetViewBlocksOptionsByTypeAction::class)
            ->execute('headernav', false);

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

                ->options($options)
                ->columnSpanFull(),
                */
                Select::make('view')

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
