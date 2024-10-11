<?php

declare(strict_types=1);

namespace Modules\Cms\Filament\Resources\PageContentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Cms\Filament\Resources\PageContentResource;

class CreatePageContent extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = PageContentResource::class;
}
