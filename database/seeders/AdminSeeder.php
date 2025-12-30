<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; 
use Spatie\Permission\Models\Role; 
use Spatie\Permission\Models\Permission; 

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $adminRole = Role::firstOrCreate(['name' => 'admin']);

       
        $admin = User::firstOrCreate(

            [
                'email' => 'admin@gmail.com',
            ],[

                'name' => 'Super Admin',
             
                'password' => bcrypt('@dmiN1234'),
            ]
        );
        if (!$admin->hasRole('admin')) {

            $admin->assignRole($adminRole);
        }
    }
}
