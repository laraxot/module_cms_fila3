<?php

use Modules\User\Models\User;
use Modules\Cms\Models\Module;

uses(Tests\TestCase::class);

it('user admin can view main dashboard', function () {
    $super_admin_user = User::role('super-admin')->first();

    $modules_name = collect(app(Module::class)->getRows())->pluck('name')->all();
    // dddx($modules_name);
    $this->actingAs($super_admin_user)->get('/admin')->assertRedirect('admin/main-dashboard');
    $this->actingAs($super_admin_user)->get('/admin/main-dashboard')->assertStatus(200); //->assertSee($modules_name);
});

it('guest user can view main dashboard', function () {
    $user = User::all()->first();

    $this->actingAs($user)->get('/admin')->assertRedirect('admin/main-dashboard');
    $this->actingAs($user)->get('/admin/main-dashboard')->assertStatus(200);

});