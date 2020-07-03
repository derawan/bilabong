<?php

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // foreach (config('project.admin.roles') as $role) {
        //     Role::firstOrCreate([
        //         'guard_name' => 'admin',
        //         'name' => $role
        //     ]);
        // };

        // $admins = [
        //     [
        //         'role' => 'administrator',
        //         'name' => 'Admin',
        //         'email' => 'administrator@gmail.com',
        //         'password' => 'secret',
        //         'phone' => '081293728732',
        //     ],
        //     [
        //         'role' => 'moderator',
        //         'name' => 'Moderator',
        //         'email' => 'moderator@gmail.com',
        //         'password' => 'secret',
        //         'phone' => '08111367865',
        //     ],
        //     [
        //         'role' => 'manager',
        //         'name' => 'Manager',
        //         'email' => 'manager@gmail.com',
        //         'password' => 'secret',
        //         'phone' => '08111167865',
        //     ],
        // ];

        // foreach ($admins as $admin) {
        //     $exist = User::where('email', $admin['email'])->first();
        //     if(empty($exist)){
        //         $super_admin = User::firstOrCreate([
        //             'name' => $admin['name'],
        //             'email' => $admin['email'],
        //             'email_verified_at' => now(),
        //             'password' => bcrypt($admin['password']),
        //             'phone' => $admin['phone'],
        //             'user_type' => $admin['role'],
        //             'remember_token' => Str::random(10),
        //             'user_status' => 'ACTIVE'
        //         ]);
        //         $super_admin->assignRole($admin['role']);
        //     }
        // }
        $this->create_default_user();
    }

    public function create_default_user()
    {
        $ts = now();
        /* Create Default User */

        $fs = new \Illuminate\Filesystem\Filesystem();
        $orang_image = $fs->glob(public_path()."/assets/themes/adminlte/img/people/*.jpg");

        $admin = \App\Models\User::create([
            'name' => 'administrator',
            'email' => 'admin@mail.net',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'phone'=> '081293728732',
            'user_status'=>'ACTIVE'
        ]);

        $manager = \App\Models\User::create([
            'name' => 'manager',
            'email' => 'manager@mail.net',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'phone'=> '081293728733',
            'user_status'=>'ACTIVE'
        ]);

        $content = \App\Models\User::create([
            'name' => 'content',
            'email' => 'content@mail.net',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'phone'=> '081293728731',
            'user_status'=>'ACTIVE'
        ]);

        $users = factory(\App\Models\User::class, 50)->create();

        $role_1 = Role::create(
            ['name'=>'Administrator','guard_name'=>'admin']
        );
        $role_2 = Role::create(
            ['name'=>'Manager','guard_name'=>'admin']
        );
        $role_3 = Role::create(
            ['name'=>'Content Creator','guard_name'=>'admin']
        );

        Permission::create(["name"=>"system_setting", "guard_name"=>"admin"]);
        Permission::create(["name"=>"file_manager", "guard_name"=>"admin"]);
        Permission::create(["name"=>"show_log", "guard_name"=>"admin"]);
        Permission::create(["name"=>"download_log", "guard_name"=>"admin"]);
        Permission::create(["name"=>"delete_log", "guard_name"=>"admin"]);


        $p1_1 = Permission::create(["name"=>"add_permission", "guard_name"=>"admin"]);
        $p1_2 = Permission::create(["name"=>"delete_permission", "guard_name"=>"admin"]);
        $p1_3 = Permission::create(["name"=>"browse_permission", "guard_name"=>"admin"]);

        $p2_1 = Permission::create(["name"=>"add_role", "guard_name"=>"admin"]);
        $p2_2 = Permission::create(["name"=>"edit_role", "guard_name"=>"admin"]);
        $p2_3 = Permission::create(["name"=>"delete_role", "guard_name"=>"admin"]);
        $p2_4 = Permission::create(["name"=>"browse_role", "guard_name"=>"admin"]);

        $p3_1 = Permission::create(["name"=>"add_user", "guard_name"=>"admin"]);
        $p3_2 = Permission::create(["name"=>"edit_user", "guard_name"=>"admin"]);
        $p3_3 = Permission::create(["name"=>"delete_user", "guard_name"=>"admin"]);
        $p3_4 = Permission::create(["name"=>"browse_user", "guard_name"=>"admin"]);
        Permission::create(["name"=>"download_user", "guard_name"=>"admin"]);
        Permission::create(["name"=>"upload_user", "guard_name"=>"admin"]);

        $p4_1 = Permission::create(["name"=>"add_page", "guard_name"=>"admin"]);
        $p4_2 = Permission::create(["name"=>"edit_page", "guard_name"=>"admin"]);
        $p4_3 = Permission::create(["name"=>"delete_page", "guard_name"=>"admin"]);
        $p4_4 = Permission::create(["name"=>"browse_page", "guard_name"=>"admin"]);
        $p4_5 = Permission::create(["name"=>"publish_page", "guard_name"=>"admin"]);

        $p5_1 = Permission::create(["name"=>"add_news", "guard_name"=>"admin"]);
        $p5_2 = Permission::create(["name"=>"edit_news", "guard_name"=>"admin"]);
        $p5_3 = Permission::create(["name"=>"delete_news", "guard_name"=>"admin"]);
        $p5_4 = Permission::create(["name"=>"browse_news", "guard_name"=>"admin"]);
        $p5_5 = Permission::create(["name"=>"publish_news", "guard_name"=>"admin"]);

        $p6_1 = Permission::create(["name"=>"add_article", "guard_name"=>"admin"]);
        $p6_2 = Permission::create(["name"=>"edit_article", "guard_name"=>"admin"]);
        $p6_3 = Permission::create(["name"=>"delete_article", "guard_name"=>"admin"]);
        $p6_4 = Permission::create(["name"=>"browse_article", "guard_name"=>"admin"]);
        $p6_5 = Permission::create(["name"=>"publish_article", "guard_name"=>"admin"]);

        $p7_1 = Permission::create(["name"=>"add_media", "guard_name"=>"admin"]);
        $p7_2 = Permission::create(["name"=>"edit_media", "guard_name"=>"admin"]);
        $p7_3 = Permission::create(["name"=>"delete_media", "guard_name"=>"admin"]);
        $p7_4 = Permission::create(["name"=>"browse_media", "guard_name"=>"admin"]);
        $p7_5 = Permission::create(["name"=>"publish_media", "guard_name"=>"admin"]);

        // administrator
        $role_1->givePermissionTo($p1_1);
        $role_1->givePermissionTo($p1_2);
        $role_1->givePermissionTo($p1_3);
        $role_1->givePermissionTo($p2_1);
        $role_1->givePermissionTo($p2_2);
        $role_1->givePermissionTo($p2_3);
        $role_1->givePermissionTo($p2_4);
        $role_1->givePermissionTo($p3_1);
        $role_1->givePermissionTo($p3_2);
        $role_1->givePermissionTo($p3_3);
        $role_1->givePermissionTo($p3_4);
        $role_1->givePermissionTo($p4_1);
        $role_1->givePermissionTo($p4_2);
        $role_1->givePermissionTo($p4_3);
        $role_1->givePermissionTo($p4_4);
        $role_1->givePermissionTo($p4_5);
        $role_1->givePermissionTo($p5_1);
        $role_1->givePermissionTo($p5_2);
        $role_1->givePermissionTo($p5_3);
        $role_1->givePermissionTo($p5_4);
        $role_1->givePermissionTo($p5_5);
        $role_1->givePermissionTo($p6_1);
        $role_1->givePermissionTo($p6_2);
        $role_1->givePermissionTo($p6_3);
        $role_1->givePermissionTo($p6_4);
        $role_1->givePermissionTo($p6_5);
        $role_1->givePermissionTo($p7_1);
        $role_1->givePermissionTo($p7_2);
        $role_1->givePermissionTo($p7_3);
        $role_1->givePermissionTo($p7_4);
        $role_1->givePermissionTo($p7_5);

        // manager
        $role_2->syncPermissions([
            $p3_1,$p3_2,$p3_3,$p3_4,
            $p4_1,$p4_2,$p4_3,$p4_4,$p4_5,
            $p5_1,$p5_2,$p5_3,$p5_4,$p5_5,
            $p6_1,$p6_2,$p6_3,$p6_4,$p6_5,
            $p7_1,$p7_2,$p7_3,$p7_4,$p7_5
        ]);

        // content creator
        $role_3->syncPermissions([
            $p4_1,$p4_2,$p4_3,$p4_4,$p4_5,
            $p5_1,$p5_2,$p5_3,$p5_4,$p5_5,
            $p6_1,$p6_2,$p6_3,$p6_4,$p6_5,
            $p7_1,$p7_2,$p7_3,$p7_4,$p7_5
        ]);

          $admin->assignRole($role_1);
        $manager->assignRole($role_2);
        $content->assignRole($role_3);

        $faker =  \Faker\Factory::create();
        for($i=4;$i<50;$i++)
        {
            $r = null;
            $user = \App\Models\User::find($i);
            switch ($faker->numberBetween(1,2))
            {
                case 1: $r = $role_1; break;
                case 2: $r = $role_2; break;
                case 3: $r = $role_3; break;
            }
            if ($user)
                $user->assignRole($r);

            $file_index = $faker->numberBetween(1,count($orang_image)-1);
            try {
                if ($user)
                    $user->copyMedia($orang_image[$file_index])->toMediaCollection('avatars', 'avatar');
            }
            catch (Exception $except) {}

        }



    }
}
