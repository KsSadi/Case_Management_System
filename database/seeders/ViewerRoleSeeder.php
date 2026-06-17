<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class ViewerRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Create the 'viewer' role if not exists
        $role = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'admin']);

        // 2. Fetch all view/report permissions
        $viewPermissions = [
            'dashboard.view',
            'user.view',
            'branch.view',
            'route.view',
            'type.view',
            'division.view',
            'court.view',
            'case.view',
            'project.view',
            'advocate.view',
            'company.view',
            'history.view',
            'report.date',
            'report.filter',
            'report.month',
        ];

        foreach ($viewPermissions as $permName) {
            // Find or create permission
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'admin']);
        }
        // Sync permission to the viewer role
        $role->syncPermissions($viewPermissions);

        // 3. Create a default viewer user if it doesn't exist
        $email = 'viewer@prathomik.com';
        if (is_null(Admin::where('email', $email)->first())) {
            $admin = new Admin();
            $admin->name = "Viewer User";
            $admin->email = $email;
            $admin->username = "viewer";
            $admin->password = Hash::make('viewer1234');
            $admin->save();
            $admin->assignRole('viewer');
        }
    }
}
