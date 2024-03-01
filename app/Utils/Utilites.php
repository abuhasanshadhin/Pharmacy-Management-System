<?php


namespace App\Utils;


class Utilites
{


    public static function languages()
    {
        $languages = collect([
            ['name'=>'English','icon'=>'united-states.svg', 'lang' => 'en'],
            ['name'=>'Spanish','icon'=>'spain.svg', 'lang' => 'es'],
            ['name'=>'German','icon'=>'germany.svg', 'lang' => 'de'],
            ['name'=>'Japanese','icon'=>'japan.svg', 'lang' => 'ja'],
            ['name'=>'French','icon'=>'france.svg', 'lang' => 'fr'],
            ['name'=>'Arabic','icon'=>'arabic.png', 'lang' => 'ar'],
            ['name'=>'Bangla','icon'=>'bangladesh.svg', 'lang' => 'bn'],
        ]);
        return $languages;
    }


    public static function permissions()
    {
        return require __DIR__ . '/permissions.php';
    }
}
