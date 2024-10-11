<?php

declare(strict_types=1);

namespace Modules\User\Filament\Clusters\Appearance\Pages;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
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
class Logo extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'user::filament.clusters.appearance.pages.logo';

    protected static ?string $cluster = Appearance::class;

    protected static ?int $navigationSort = 1;

    public ?array $logoData = [];

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
                FileUpload::make('logo'),
                FileUpload::make('logo_dark'),
                TextInput::make('logo_height')->numeric()->default(32),
                // ])->columns(2),
            ])->columns(2)
            // ->model($this->getUser())
            ->statePath('logoData');
    }

    protected function getUpdateLogoFormActions(): array
    {
        return [
            Action::make('updateLogoAction')
                ->label(__('filament-panels::pages/auth/edit-profile.form.actions.save.label'))
                ->submit('editLogoForm'),
        ];
    }

    public function updateLogo(): void
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
