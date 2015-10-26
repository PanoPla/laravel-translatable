<?php namespace panopla\Translatable;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class Translatable
{
    /**
     * Getter/setter for session language.
     *
     * @param $code string If null, the actual language is returned.
     * Otherwise, try to set the actual $code as the session language.
     * @return string The actual session language
     * @throws InvalidLanguageCode If the language is not specified on the configuration file
     */
    public static function sessionLanguage($code = null)
    {
        $session_parameter = Config::get('translatable.session_parameter', 'language');

        if (!is_null($code)) {
            //Setter
            $allowed = static::validateLanguageCode($code);


            if (!$allowed) {
                throw new InvalidLanguageCode;
            }

            Session::put($session_parameter, $code);

            return Session::get($session_parameter);
        } else {
            //Getter
            if (Session::has('language')) {
                return Session::get($session_parameter);
            }

            return self::fallbackLanguage();
        }
    }

    public static function fallbackLanguage()
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