<?php

uses(Modules\Cms\Tests\TestHelper::class);

beforeEach(function () {
    $this->super_admin_user = $this->getSuperAdminUser();
    $this->no_super_admin_user = $this->getNoSuperAdminUser();
});

it('user admin can view main dashboard', function () {
    $modules_name = $this->getModuleNameLists();

    $this->actingAs($this->super_admin_user)->get('/admin')->assertRedirect('admin/main-dashboard');
    $this->actingAs($this->super_admin_user)->get('/admin/main-dashboard')->assertStatus(200); //->assertSee($modules_name);
});

it('guest user can view main dashboard', function () {
    $this->actingAs($this->no_super_admin_user)->get('/admin')->assertRedirect('admin/main-dashboard');
    $this->actingAs($this->no_super_admin_user)->get('/admin/main-dashboard')->assertStatus(200);
});

it('the user views navigation modules entries based on their role', function () {
    $item_navs_roles = $this->getUserNavigationItem($this->super_admin_user);
    foreach($item_navs_roles as $item_nav_role){
        $this->actingAs($this->super_admin_user)->get('/admin/main-dashboard')->assertSee($item_nav_role);
    }
});

it('the user views navigation modules entries based on their no role', function () {
    // ddd($this->getMainAdminNavigationItems());

    $diff = $this->getMainAdminNavigationItems()->diffAssoc($this->getUserNavigationItem($this->super_admin_user));
    dddx($diff);



    // $item_navs_roles = $this->getNavigationItemRoles($this->super_admin_user);
    // foreach($item_navs_roles as $item_nav_role){
    //     $this->actingAs($this->super_admin_user)->get('/admin/main-dashboard')->assertSee($item_nav_role);
    // }
});

it('user admin can view module dashboard', function () {
    // $module_name = 'BarberShop';

    // $this->get('/admin')->dd();

    // $this->actingAs($super_admin_user)->get('/admin')->assertRedirect('admin/main-dashboard');
    $this->actingAs($this->super_admin_user)->get('http://multiv.local/barbershop/admin/dashboard')->assertStatus(200); //->assertSee($modules_name);
})->todo();