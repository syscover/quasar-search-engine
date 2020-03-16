<?php

use Illuminate\Database\Seeder;
use Quasar\Admin\Models\Package;

class SearchEnginePackageSeeder extends Seeder
{
    public function run()
    {
        Package::insert([
            ['id' => 4, 'uuid' => '4b41ab02-e761-413e-bfd7-8a21d9f96da9', 'name' => 'Search Engine', 'root' => 'search-engine', 'sort' => 4, 'is_active' => 1]
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="SearchEnginePackageSeeder"
 */
