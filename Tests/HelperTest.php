<?php

namespace Modules\Cms\Tests;

use Modules\User\Models\User;
use Tests\CreatesApplication;
use Modules\Cms\Models\Module;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestHelper extends BaseTestCase
{
    use CreatesApplication;

    public function getSuperAdminUser(){
        return User::role('super-admin')->first();
    }

    public function getNoSuperAdminUser(){
        return User::all()
            ->map(function($item){
                if(!$item->hasRole('super-admin')){
                    return $item;
                }
            })->first();
    }

    public function getModuleNameLists(){
        return collect(app(Module::class)
            ->getRows())
            ->pluck('name')
            ->all();
    }
}
