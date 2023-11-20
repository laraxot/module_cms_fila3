<?php

declare(strict_types=1);

namespace Modules\Cms\Filament\Front\Pages;

use Filament\Pages\Page;

class Welcome extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'cms::filament.front.pages.welcome';

    protected static string $layout = 'pub_theme::layouts.app';
}
