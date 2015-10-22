<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use panopla\Translatable\Language;

class LanguageSeeder extends Seeder
{

    public function run()
    {
        DB::table('language')->delete();

        Language::create([
            'id' => '1'
            , 'language_code' => 'en'
            , 'language_name' => 'English'
        ]);

        Language::create([
            'language_code' => 'pt-BR'
            , 'language_name' => 'Brazilian portuguese'
        ]);


    }

}