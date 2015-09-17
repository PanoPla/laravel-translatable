<?php

namespace panopla\Translatable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class Language extends Model
{

    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('translatable.language_table', 'language');
    }

    /**
     * Getter and setter function for the current session language
     *
     * @param int $language
     * @return mixed
     */
    public static function setSessionLanguage($language)
    {
        Session::put('language', $language);

        return Session::get('language');
    }

    public static function getSessionLanguage()
    {
        if (Session::has('language')) {
            return Session::get('language');
        }

        return self::getFallback();
    }

    public static function getFallback()
    {
        return Config::get('translatable.fallback');
    }


}