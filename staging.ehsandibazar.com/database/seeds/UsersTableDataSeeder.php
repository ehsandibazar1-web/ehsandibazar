<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'active' => 1,
            'level' => \App\Utility\Level::SUPER_ADMIN,
            'name' => "reza",
            'family' => "kia",
            'email' => "rezakiyamanesh@gmail.com",
            'mobile' => "09390383238",
            'password' => bcrypt("123321"),
        ]);
        DB::table('attribute_types')->insert([
            ['user_id' => 1, 'name' => "رنگ", 'status' => 0,],
            ['user_id' => 1, 'name' => "سایز", 'status' => 0,],
            ['user_id' => 1, 'name' => "بدون خصوصیت", 'status' => 1,],
        ]);
        DB::table('attribute_type_values')->insert([
            'user_id' => 1,
            'attribute_type_id' => 3,
            'value' => "بدون خصوصیت",
            'status' => 1,
        ]);

    }
}
