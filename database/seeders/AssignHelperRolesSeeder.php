<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role; 
use Spatie\Permission\Models\Permission; 
class AssignHelperRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $helper = User::whereHas('helper')->get();

        foreach ($helper as $user) {

            if (!$user->hasRole('helper')) {
            
                $user->assignRole('helper');
            
                $this->command->info($user->email . " => role assigned");
            
            } else {
            
                $this->command->info($user->email . " => already has role");
            }
        }

        $this->command->info('All helper roles have been checked and assigned.');
    }
    
}
