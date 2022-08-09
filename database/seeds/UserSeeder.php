<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'thanhphu2410@gmail.com',
            'gender' => 'Nam',
            'date_of_birth' => '15/11/2000',
            'phone'     => '0932177045',
            'role_id'   => 1,
            'password' => Hash::make('123456'),
            'avatar' => 'images/avatar-default.svg',
            'created_at' => now()
        ]);
    }
}
