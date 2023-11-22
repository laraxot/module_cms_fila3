<?php

declare(strict_types=1);
use Modules\Cms\Filament\Front\Pages\Welcome;

use Illuminate\Support\Facades\Route;

Route::get('/{lang?}/{container0?}', '\\' . Welcome::class)->name('test');
