<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Admin::where('email','admin@email.com')->first() === null){
            $admin              = new Admin();
            $admin->name        = 'Administrator';
            $admin->email       = 'admin@email.com';
            $admin->password    = Hash::make('password');
            $admin->save();
        }
    }
}
