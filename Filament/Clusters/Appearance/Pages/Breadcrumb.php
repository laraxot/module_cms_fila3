<?php

declare(strict_types=1);

namespace Modules\Cms\Filament\Clusters\Appearance\Pages;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Arr;
use Modules\Cms\Filament\Clusters\Appearance;
use Modules\Tenant\Services\TenantService;
use Webmozart\Assert\Assert;

/**
 * @property Forms\ComponentContainer $form
 */
class Breadcrumb extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'cms::filament.clusters.appearance.pages.headernav';

    protected static ?string $cluster = Appearance::class;

    protected static ?int $navigationSort = 2;

    public function mount(): void
    {
        $this->fillForms();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('class'),
                TextInput::make('style'),
            ])->columns(2)
            ->statePath('data');
    }

    public function updateData(): void
    {
        try {
            $data = $this->form->getState();
            $up = [
                'breadcrumb' => $data,
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
        Assert::isArray($data = Arr::get($data, 'breadcrumb', []));

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
