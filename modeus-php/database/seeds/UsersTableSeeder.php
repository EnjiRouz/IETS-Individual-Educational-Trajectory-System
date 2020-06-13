<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        DB::table('role_user')->truncate();

        $adminRole=Role::where("role_name", "admin")->first();
        $userRole=Role::where("role_name", "user")->first();

        $admin=User::create([
            "name"  =>  "admin",
            "password"  =>  bcrypt("nimda")
        ]);

        $user=User::create([
            "name"  =>  "user",
            "password"  =>  bcrypt("resu")
        ]);

        $admin->roles()->attach($adminRole);
        $user->roles()->attach($userRole);
    }
}
