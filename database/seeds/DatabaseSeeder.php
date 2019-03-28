<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Group;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // copied from https://stackoverflow.com/questions/20546253/how-to-reset-auto-increment-in-laravel-user-deletion
        // truncate is needed so that the autoincrement id counter will be reset to zero
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $this->call(UserTableSeeder::class);
        // $this->call(GroupTableSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}

class UserTableSeeder extends Seeder
{
    public function run()
    { 
        DB::table('users')->truncate();
        User::create(['name' => 'Admin01', 'email' => 'Admin01@gmail.com', 'password' => bcrypt('12345678')]);
        User::create(['name' => 'John'   , 'email' => 'John@gmail.com'   , 'password' => bcrypt('12345678')]);
        User::create(['name' => 'Lee'    , 'email' => 'Lee@gmail.com'    , 'password' => bcrypt('12345678')]);
    }
}

// class GroupTableSeeder extends Seeder 
// {
//     public function run()
//     {
//         DB::table('candidates')->truncate();
//         Group::create(['name' => 'Abu Bakar Muhammad', 'party_id' => 3]);
//         Group::create(['name' => 'Ng Pei Li'         , 'party_id' => 3]);
//         Group::create(['name' => 'Ranjit Singh Deo'  , 'party_id' => 3]);
//         Group::create(['name' => 'Foo Yoke Wei'      , 'party_id' => 1]);
//         Group::create(['name' => 'Chia Kim Hooi'     , 'party_id' => 2]);
//     }
// }
