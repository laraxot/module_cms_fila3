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

it('the user views navigation modules entries based on their role', function () {
    $super_admin_user = $this->getSuperAdminUser();
    // $user = $this->getNoSuperAdminUser();
    // dddx($user->getRoleNames());
    $item_navs = $this->getMainAdminNavigationItems();
    $item_navs_roles = $this->getNavigationItemRoles($super_admin_user);
    foreach($item_navs as $item){
        dddx([$super_admin_user->getRoleNames(), $item]);
    }

    // foreach($super_admin_user->getRoleNames() as $role){
    //     dddx($role);
    // }
});

it('user admin can view module dashboard', function () {
    $super_admin_user = $this->getSuperAdminUser();

    // $module_name = 'BarberShop';

    // $this->get('/admin')->dd();

    // $this->actingAs($super_admin_user)->get('/admin')->assertRedirect('admin/main-dashboard');
    $this->actingAs($super_admin_user)->get('http://multiv.local/barbershop/admin/dashboard')->assertStatus(200); //->assertSee($modules_name);
})->todo();