<?php

declare(strict_types=1);

namespace Modules\Cms\Filament\Fields;

use Filament\Forms\Components\Builder;
use Modules\Blog\Filament\Blocks\ArticleList;
use Modules\Blog\Filament\Blocks\DropdownMenu;
use Modules\Blog\Filament\Blocks\ProfileOrLogin;

class LeftSidebarContent
{
    public static function make(
        string $name,
        string $context = 'form',
    ): Builder {
        return Builder::make($name)
            ->blocks([
                DropdownMenu::make(context: $context),
                ArticleList::make(context: $context),
                ProfileOrLogin::make(context: $context),
            ])
            ->collapsible();
    }
}
