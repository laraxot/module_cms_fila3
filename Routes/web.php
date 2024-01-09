<?php

declare(strict_types=1);
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use Modules\Cms\Filament\Front\Pages\Home;
=======
>>>>>>> 61c773d (first)
use Modules\Cms\Filament\Front\Pages\Welcome;
use Modules\Cms\Http\Controllers\PageController;

Route::get('/{lang?}/{container0?}/{item0?}/{container1?}/{item1?}/{container2?}/{item2?}', '\\'.Welcome::class)->name('test');
<<<<<<< HEAD
Route::get('/', '\\'.Home::class)->name('home');
=======
>>>>>>> 61c773d (first)

// Route::get('/{container0}/{item0?}/{container1?}/{item1?}/{container2?}/{item2?}', PageController::class);
