<?php

declare(strict_types=1);

namespace Modules\Cms\Filament\Clusters\Appearance\Pages;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Forms\Components\Select;
=======
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
>>>>>>> f59ce63 (📝 (Headernav.php): Add ColorPicker and FileUpload components to the form for appearance settings)
=======
>>>>>>> f48fdb3 (.)
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
<<<<<<< HEAD
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
=======
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
<<<<<<< HEAD
>>>>>>> f59ce63 (📝 (Headernav.php): Add ColorPicker and FileUpload components to the form for appearance settings)
use Modules\Cms\Filament\Clusters\Appearance;
<<<<<<< HEAD
use Modules\Tenant\Services\TenantService;
use Modules\UI\Filament\Forms\Components\RadioImage;
use Modules\Xot\Actions\View\GetViewsSiblingsAndSelfAction;
use Modules\Xot\Services\FileService;
use Webmozart\Assert\Assert;
=======
<<<<<<< HEAD
=======
use Filament\Forms\Concerns\InteractsWithForms;
=======
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Modules\Cms\Filament\Clusters\Appearance;

>>>>>>> ac32394 (up)
use function Safe\file_get_contents;
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
>>>>>>> a806d34 (.)
=======
use function Safe\file_put_contents;
>>>>>>> 784fa4b (up)
<<<<<<< HEAD
>>>>>>> b7576b0 (🔧 (Headernav.php): resolve conflict by keeping the file_put_contents function import)
=======
=======
>>>>>>> 53cf86e (.)
<<<<<<< HEAD
>>>>>>> 9726675 (🔧 (Headernav.php): resolve conflicts and clean up unnecessary merge markers in the file)
=======
=======
use function Safe\file_put_contents;
>>>>>>> 3a6d4fa (.)
>>>>>>> e59a777 (🔧 (Headernav.php): resolve merge conflicts and clean up unnecessary code leftovers)

/**
 * @property Forms\ComponentContainer $form
 */
class Headernav extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'cms::filament.clusters.appearance.pages.headernav';

    protected static ?string $cluster = Appearance::class;

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public function mount(): void
    {
<<<<<<< HEAD
        $this->fillForms();
    }

=======
        $this->checkOrCreateConfigAppearance();

        // $filePath = config_path($filePath); // Percorso del file di configurazione
        $path = implode('/', array_reverse(explode('.', request()->getHost())));
        $filePath = 'config/'.$path.'/appearance.php';
        $key = 'other3';
        // $value = 'other2';
        $value = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => [
                'subkey1' => 'subvalue1',
                'subkey2' => 'subvalue2',
            ],
        ];
<<<<<<< HEAD
=======

        // $this->addConfigValue(base_path('config/'.$path.'/appearance.php'), $key, $value);
>>>>>>> 5fa39c3 (.)

        // $this->addConfigValue(base_path('config/'.$path.'/appearance.php'), $key, $value);

<<<<<<< HEAD
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
=======
        // dddx(config('appearance'));

>>>>>>> ac32394 (up)
        $this->fillForms();
    }

<<<<<<< HEAD
    public function addConfigValue(string $filePath, int $key, string $value): void
=======
    public function addConfigValue($filePath, $key, $value)
>>>>>>> 5fa39c3 (.)
    {
        // Leggi il contenuto del file
        $config = file_get_contents($filePath);

        // Trasforma il contenuto del file in un array PHP
<<<<<<< HEAD
<<<<<<< HEAD
        $configArray = include($filePath);
=======
        $configArray = include $filePath;
>>>>>>> ac32394 (up)
=======
        $configArray = include $filePath;
>>>>>>> 5fa39c3 (.)

        // Aggiungi o aggiorna il valore dell'array
        $configArray[$key] = $value;

        // Converte l'array PHP in una stringa di codice PHP
<<<<<<< HEAD
<<<<<<< HEAD
        $newConfig = '<?php declare(strict_types=1); return ' . var_export($configArray, true) . ';';
=======
        $newConfig = '<?php declare(strict_types=1); return '.var_export($configArray, true).';';
>>>>>>> ac32394 (up)
=======
        $newConfig = '<?php declare(strict_types=1); return '.var_export($configArray, true).';';
>>>>>>> 5fa39c3 (.)

        // Scrivi la nuova configurazione nel file
        file_put_contents($filePath, $newConfig);
        Artisan::call('optimize:clear');
    }

<<<<<<< HEAD
<<<<<<< HEAD

    public function checkOrCreateConfigAppearance()
    {
        if(!config('appearance')) {
=======
    public function checkOrCreateConfigAppearance(): void
    {
<<<<<<< HEAD
        if(!config('appearance')){
>>>>>>> a806d34 (.)
=======
        if (! config('appearance')) {
>>>>>>> ac32394 (up)
            // Creare un file di configurazione in modo programmatico
            $path = implode('/', array_reverse(explode('.', request()->getHost())));
            $filePath = base_path('config/'.$path.'/appearance.php');
            $configContent = <<<'PHP'
=======
    public function checkOrCreateConfigAppearance()
    {
        if (! config('appearance')) {
            // Creare un file di configurazione in modo programmatico
            $path = implode('/', array_reverse(explode('.', request()->getHost())));
            $filePath = base_path('config/'.$path.'/appearance.php');
            $configContent = <<<PHP
>>>>>>> 5fa39c3 (.)
            <?php

            declare(strict_types=1);

            return [

            ];
            PHP;
            file_put_contents($filePath, $configContent);
            // dddx(config('appearance'));
        }
    }

<<<<<<< HEAD
<<<<<<< HEAD







=======
>>>>>>> ac32394 (up)
=======
>>>>>>> 5fa39c3 (.)
>>>>>>> f59ce63 (📝 (Headernav.php): Add ColorPicker and FileUpload components to the form for appearance settings)
    protected function fillForms(): void
    {
        Assert::isArray($data = TenantService::config('appearance'));
        Assert::isArray($data = Arr::get($data, 'headernav', []));

        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        $view = 'cms::components.headernav.simple';
        $view_p = Str::beforeLast($view, '.');
        $views = app(GetViewsSiblingsAndSelfAction::class)->execute($view);

        $options = Arr::mapWithKeys($views, function ($item) use ($view_p) {
            $k = $view_p.'.'.$item;

            return [$k => $k];
        });

        /*
        $options = Arr::map($views, function ($view) {
            return FileService::asset('ui::img/headernav/screenshots/'.$view.'.png');
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
}
