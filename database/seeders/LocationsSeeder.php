<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use App\Models\Subdistrict;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subdistrict::truncate();
        City::truncate();
        Province::truncate();
        $type = strtolower(env('RAJAONGKIR_PACKAGE', 'Key Dari ENV'));
        $enpoint = "https://".$type.".rajaongkir.com/api/";
        $httpProvinces = Http::get($enpoint.'province', [
            'key' => env('RAJAONGKIR_API_KEY', 'Key Dari ENV')
        ])->json();
        $allProvinces = $httpProvinces['rajaongkir']['results'];
        foreach ($allProvinces as $key => $value) {
            Province::create([
                'province_id' => $value['province_id'],
                'name'        => $value['province'],
            ]);

            $httpCity = Http::get($enpoint.'city', [
                'key' => env('RAJAONGKIR_API_KEY', 'Key Dari ENV'),
                'province' => $value['province_id']
            ])->json();
            $listCity = $httpCity['rajaongkir']['results'];
            foreach ($listCity as $k => $v) {
                City::create([
                    'province_id'   => $value['province_id'],
                    'city_id'       => $v['city_id'],
                    'name'          => $v['city_name'],
                    'type'          => $v['type'],
                    'postal_code'   => $v['postal_code'],
                ]);

                if($type == 'pro') {
                    $httpSubdistrict = Http::get($enpoint.'subdistrict', [
                        'key' => env('RAJAONGKIR_API_KEY', 'Key Dari ENV'),
                        'city' => $v['city_id']
                    ])->json();
                    $listSubdistrict = $httpSubdistrict['rajaongkir']['results'];
                    foreach ($listSubdistrict as $kSub => $vSub) {
                        Subdistrict::create([
                            'province_id'       => $value['province_id'],
                            'city_id'           => $v['city_id'],
                            'subdistrict_id'    => $vSub['subdistrict_id'],
                            'subdistrict_name'  => $vSub['subdistrict_name'],
                        ]);
                    }
                }
            }
        }

    }
}
