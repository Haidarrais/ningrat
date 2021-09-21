<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = Role::create(['name' => 'superadmin']);
        $distributor = Role::create(['name' => 'distributor']);
        $agent_plus = Role::create(['name' => 'agent+']);
        $agent = Role::create(['name' => 'agent']);
        $sub_agent = Role::create(['name' => 'subagent']);
        $reseller = Role::create(['name' => 'reseller']);
        $customer = Role::create(['name' => 'customer']);

        $user_superadmin = User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@gmail.com',
            'password' => '123456',
            'email_verified_at' => now()
        ]);
        $user_superadmin->assignRole($superadmin);

        $user_distributor = User::create([
            'name' => 'Distributor',
            'email' => 'distributor@gmail.com',
            'password' => '123456',
            'email_verified_at' => now()
        ]);
        $user_distributor->assignRole($distributor);

        $user_agent_plus = User::create([
            'name' => 'Agent Peles',
            'email' => 'agent_plus@gmail.com',
            'password' => '123456',
            'email_verified_at' => now()
        ]);
        $user_agent_plus->assignRole($agent_plus);

        $user_agent = User::create([
            'name' => 'Agent',
            'email' => 'agent@gmail.com',
            'password' => '123456',
            'email_verified_at' => now()
        ]);
        $user_agent->assignRole($agent);

        $user_sub_agent = User::create([
            'name' => 'Sub Agent',
            'email' => 'subagent@gmail.com',
            'password' => '123456',
            'email_verified_at' => now()
        ]);
        $user_sub_agent->assignRole($sub_agent);

        $user_reseller = User::create([
            'name' => 'Reseller',
            'email' => 'reseller@gmail.com',
            'password' => '123456',
            'email_verified_at' => now()
        ]);
        $user_reseller->assignRole($reseller);

        $user_customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@gmail.com',
            'password' => '123456',
            'email_verified_at' => now()
        ]);
        $user_customer->assignRole($customer);
    }
}
