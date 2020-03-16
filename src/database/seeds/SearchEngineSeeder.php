<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SearchEngineSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        $this->call(SearchEnginePackageSeeder::class);
        
        Model::reguard();
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="SearchEngineSeeder"
 */
