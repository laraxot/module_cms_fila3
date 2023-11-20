<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/{lang?}/{container0?}', '\Modules\Cms\Filament\Front\Pages\Welcome')->name('test');
