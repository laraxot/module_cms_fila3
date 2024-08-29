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
<<<<<<< HEAD
use Modules\Tenant\Services\TenantService;
use Modules\UI\Filament\Forms\Components\RadioImage;
use Modules\Xot\Actions\View\GetViewsSiblingsAndSelfAction;
use Modules\Xot\Services\FileService;
use Webmozart\Assert\Assert;
=======

use function Safe\file_get_contents;
use function Safe\file_put_contents;
>>>>>>> 3ab70fa (conflict)

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

        // $this->addConfigValue(base_path('config/'.$path.'/appearance.php'), $key, $value);

        // dddx(config('appearance'));

        $this->fillForms();
    }

    public function addConfigValue(string $filePath, int $key, string $value): void
    {
        // Leggi il contenuto del file
        $config = file_get_contents($filePath);

        // Trasforma il contenuto del file in un array PHP
        $configArray = include $filePath;

        // Aggiungi o aggiorna il valore dell'array
        $configArray[$key] = $value;

        // Converte l'array PHP in una stringa di codice PHP
        $newConfig = '<?php declare(strict_types=1); return '.var_export($configArray, true).';';

        // Scrivi la nuova configurazione nel file
        file_put_contents($filePath, $newConfig);
        Artisan::call('optimize:clear');
    }

    public function checkOrCreateConfigAppearance(): void
    {
        if (! config('appearance')) {
            // Creare un file di configurazione in modo programmatico
            $path = implode('/', array_reverse(explode('.', request()->getHost())));
            $filePath = base_path('config/'.$path.'/appearance.php');
            $configContent = <<<'PHP'
            <?php

            declare(strict_types=1);

            return [

            ];
            PHP;
            file_put_contents($filePath, $configContent);
            // dddx(config('appearance'));
        }
    }

>>>>>>> 3ab70fa (conflict)
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
