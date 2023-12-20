<?php

uses(Modules\Cms\Tests\TestHelper::class);

it('user admin can view main dashboard', function () {
    $super_admin_user = $this->getSuperAdminUser();

    $modules_name = $this->getModuleNameLists();

    $this->actingAs($super_admin_user)->get('/admin')->assertRedirect('admin/main-dashboard');
    $this->actingAs($super_admin_user)->get('/admin/main-dashboard')->assertStatus(200); //->assertSee($modules_name);
});

it('guest user can view main dashboard', function () {
    $user = $this->getNoSuperAdminUser();

    $this->actingAs($user)->get('/admin')->assertRedirect('admin/main-dashboard');
    $this->actingAs($user)->get('/admin/main-dashboard')->assertStatus(200);
});