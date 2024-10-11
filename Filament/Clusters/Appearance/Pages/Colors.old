<?php

declare(strict_types=1);

namespace Modules\User\Filament\Clusters\Appearance\Pages;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Filament\Clusters\Appearance;

/**
 * @property Forms\ComponentContainer $form
 */
class Colors extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'user::filament.clusters.appearance.pages.colors';

    protected static ?string $cluster = Appearance::class;

    protected static ?int $navigationSort = 3;

    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForms();
    }

    protected function fillForms(): void
    {
        // $data = $this->getUser()->attributesToArray();
        $data = [];

        $this->form->fill($data);
    }

    // protected function getForms(): array
    // {
    //    return [
    //        'editLogoForm',
    //    ];
    // }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Section::make('Profile Information')
                // ->description('Update your account\'s profile information and email address.')
                // ->schema([
                ColorPicker::make('text_color'),
                ColorPicker::make('button_color'),
                ColorPicker::make('button_text_color'),
                ColorPicker::make('input_text_color'),
                ColorPicker::make('input_border_color'),

                // ])->columns(2),
            ])->columns(3)
            // ->model($this->getUser())
            ->statePath('data');
    }

    protected function getUpdateFormActions(): array
    {
        return [
            Action::make('updateAction')
                ->label(__('filament-panels::pages/auth/edit-profile.form.actions.save.label'))
                ->submit('editForm'),
        ];
    }

    public function updateData(): void
    {
        try {
            $data = $this->form->getState();
            dddx($data);
            // $this->handleRecordUpdate($this->getUser(), $data);
        } catch (Halt $exception) {
            dddx($exception->getMessage());

            return;
        }
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        return $record;
    }
}
