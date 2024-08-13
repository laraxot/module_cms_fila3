<?php

declare(strict_types=1);

namespace Modules\Cms\Filament\Clusters\Appearance\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\File;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Modules\Cms\Filament\Clusters\Appearance;
use Filament\Forms\Concerns\InteractsWithForms;

/**
 * @property Forms\ComponentContainer $form
 */
class Headernav extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'cms::filament.clusters.appearance.pages.headernav';

    protected static ?string $cluster = Appearance::class;

    protected static ?int $navigationSort = 2;

    public ?array $data = [];

    public function mount(): void
    {
        // dddx(request()->getHost());

        // if(config('appearance')){
        //     dddx([
        //         base_path('config/local'),
        //         config('appearance.headernav.other_color'),
        //         config('appearance')
        //     ]);
        // }else{
        //     Config::set('appearance.headernav', 'Europe/Rome');
        //     // config(['appearance.headernav' => []]);
        // }


        // Creare un file di configurazione in modo programmatico
        // $filePath = config_path('appearance.php');
        // dddx($filePath);
        // $configContent = <<<PHP
        // <?php

        // declare(strict_types=1);

        // return [

        // ];
        // PHP;
        // file_put_contents($filePath, $configContent);


        // config(['appearance.headernav' => 'aaa']);
        Config::set('appearance.headernav', 'Europe/Rome');
        dddx(config('appearance'));
        
            
        // if (File::exists('path/to/file.txt')) {
        //     // Il file esiste
        // } else {
        //     // Il file non esiste
        // }
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
                ColorPicker::make('background_color'),
                FileUpload::make('background'),
                ColorPicker::make('overlay_color'),
                TextInput::make('overlay_opacity')->numeric()->minValue(0)->maxValue(100),

                // ])->columns(2),
            ])->columns(2)
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
        dddx($record);
        $record->update($data);

        return $record;
    }
}
