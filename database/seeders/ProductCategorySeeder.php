<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Category::create([
            'name' => 'kat 2',
            'slug' => 'kat-2'
        ]);

        for ($i=81; $i < 100; $i++) {
            Product::create([
                'name' => 'Prod-'.$i,
                'slug' => 'prod-'.$i,
                'category_id' => $category->id,
                'description' => 'deskriosi guys',
                'image' => 'image',
                'price' => random_int('10000', '100000'),
                'weight' => random_int('100', '500')
            ]);
        }
    }
}
