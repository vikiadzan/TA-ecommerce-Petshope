<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'=>'Administrator2',
            'email'=>'admin2@gmail.com',
            'password'=> bcrypt('admin2'),
            'email_verified_at'=>now()

        ]);
    }
}
