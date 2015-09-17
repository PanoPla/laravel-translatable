<?php

namespace panopla\Translatable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use panopla\Translatable\Exceptions\InvalidLanguageCode;

class Language extends Model
{

    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('translatable.language_table', 'language');
    }

    /**
     * Define the current session language.
     *
     * @param $code
     * @return mixed
     * @throws InvalidLanguageCode If the language is not specified on the configuration file
     */
    public static function setSessionLanguage($code)
    {
        $allowed = static::validateLanguageCode($code);

        if (!$allowed) {
            throw new InvalidLanguageCode;
        }

        Session::put('language', $code);

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

    /**
     * Reject a language code if not specified on the configuration file.
     *
     * @param $code
     * @return bool
     */
    public static function validateLanguageCode($code)
    {
        $aLanguages = Config::get('translatable.languages');

        return array_has($aLanguages, $code);

    }


}