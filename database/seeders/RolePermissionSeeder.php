<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Standart ve admin isimin 2 rol tanımlanacak.
         * Standart kulanıcının sadece düzenleme izni olacak.
         * Adminin ise düzenleme, silme, yayınlama ve yayından kaldırabilme izinleri olacak.
         */

        //Eğer varsa önceli izinleri sil
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'publish articles']);
        Permission::create(['name' => 'unpublish articles']);

        // yeni bir role tanımlanacak.
        $standartRole = Role::create(['name' => 'standart']);
        $standartRole->givePermissionTo('edit articles');

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo('edit articles');
        $adminRole->givePermissionTo('delete articles');
        $adminRole->givePermissionTo('publish articles');
        $adminRole->givePermissionTo('unpublish articles');

        // Standart kullanıcı oluştur ve ona tanımlanana rolü ata
        $standartUser = \App\Models\User::factory()->create([
            'name' => 'Standart Kullanıcı',
            'email' => 'standart@example.com',
            'password' => bcrypt('123456'),
        ]);
        $standartUser->assignRole($standartRole);

        $adminUser = \App\Models\User::factory()->create([
            'name' => 'Admin Kullanıcı',
            'email' => 'admin@example.com',
            'password' => bcrypt('123456'),
        ]);
        $adminUser->assignRole($adminRole);
    }

}
