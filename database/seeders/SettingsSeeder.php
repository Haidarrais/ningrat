<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Setting::create([
            'parent'=>0,
            'key'=>'minimal-belanja-point',
            'value'=>6000000,
            'minimal_transaction'=>6000000,
            'discount'=>10,
            'role'=>'old-distributor'
        ]);
    }
}
