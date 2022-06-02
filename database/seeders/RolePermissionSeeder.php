<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create Roles
        $roleGod = Role::create(['name' => 'god', 'guard_name' => 'admin']);
        $roleAdmin = Role::create(['name' => 'admin', 'guard_name' => 'admin']);
        $roleSubAdmin = Role::create(['name' => 'subAdmin', 'guard_name' => 'admin']);
        $roleManager = Role::create(['name' => 'manager', 'guard_name' => 'admin']);
        $roleSale = Role::create(['name' => 'sale', 'guard_name' => 'admin']);

        //Permissions
        $permissions = [
            'dashboard.view',

            //permissions names for admins
            'admin.create',
            'admin.view',
            'admin.edit',
            'admin.delete',
            'admin.approve',

            //permission name for users
            'user.create',
            'user.view',
            'user.edit',
            'user.delete',
            'user.approve',

            //permissions name for roles
            'role.create',
            'role.view',
            'role.edit',
            'role.delete',
            'role.approve',

            //permissions name for branches
            'branch.create',
            'branch.view',
            'branch.edit',
            'branch.delete',
            'branch.approve',

            //permissions name for routes
            'route.create',
            'route.view',
            'route.edit',
            'route.delete',
            'route.approve',

            //permissions name for Types
            'type.create',
            'type.view',
            'type.edit',
            'type.delete',
            'type.approve',

            //permissions name for Division
            'division.create',
            'division.view',
            'division.edit',
            'division.delete',
            'division.approve',
            //permissions name for court
            'court.create',
            'court.view',
            'court.edit',
            'court.delete',
            'court.approve',
            //permissions name for case
            'case.create',
            'case.view',
            'case.edit',
            'case.delete',
            'case.approve',

            //permissions name for project
            'project.create',
            'project.view',
            'project.edit',
            'project.delete',
            'project.approve',

            //permissions name for advocate
            'advocate.create',
            'advocate.view',
            'advocate.edit',
            'advocate.delete',
            'advocate.approve',

            //permissions name for advocate
            'history.create',
            'history.view',
            'history.edit',
            'history.delete',
            'history.approve',
            'report.date',
            'report.filter',
            'report.month',



        ];



        //Create and Assign Permissions for admin
        for ($i = 0; $i < count($permissions); $i++) {
            //Create Permission
            $permission = Permission::create(['name' => $permissions[$i], 'guard_name' => 'admin']);

            //Assign Permission to a Role
            $roleGod->givePermissionTo($permission);
        }


    }
}
