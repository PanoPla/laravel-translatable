<?php

namespace panopla\Translatable;

/**
 * Class TranslatableModel
 * @package panopla\Translatable
 */
trait TranslatableModel
{

    protected $translatable_attributes = [];

    protected $translatable_updated = false;

    public static function boot()
    {

        static::created(function (TranslatableInterface $model) {
            $model->processPendingAttributes();
        });

        static::updated(function (TranslatableInterface $model) {
            $model->processPendingAttributes();
        });

        static::saved(function (TranslatableInterface $model) {
            $model->processPendingAttributes();
        });

    }

    /**
     * @param $text
     * @param $languageCode
     * @return string
     */
    protected function getTranslatableAttribute($text, $languageCode)
    {

        if (!$this->translatable_updated && isset($this->translatable_attributes[$languageCode][$text])) {
            return $this->translatable_attributes[$languageCode][$text];
        }

        $model = $this->obtainTextModel($languageCode);

        return $model->$text;

    }

    /**
     * @param $text
     * @param null $value
     * @param $languageCode
     */
    protected function setTranslatableAttribute($text, $value, $languageCode)
    {
        if (is_array($text)) {
            $this->appendTranslatableAttributes($text, $value);
        } else {
            $this->appendTranslatableAttributes([$text => $value], $languageCode);
        }

    }

    protected function processPendingAttributes()
    {
        foreach ($this->translatable_attributes as $language_id => $attributes) {

            $model = $this->obtainTextModel($language_id);
            $model->fill($attributes);

            $model->save();
        }

        $this->translatable_updated = true;
    }

    private function obtainTextModel($languageCode)
    {
        $sClassName = (new \ReflectionClass($this))->getName();
        $sClassName .= 'Text';

        //Yet a model has many translations for different languages,
        //there is only one line for each language ( or so it should be :) )
        $result = $this->hasMany($sClassName)->firstOrNew(['language_id' => $languageCode]);

        return $result;
    }

    private function appendTranslatableAttributes(array $attributes, $languageCode)
    {
        if (!$languageCode) {
            $languageCode = Language::getFallback();
        }

        $this->translatable_updated = false;
        if (!isset($this->translatable_attributes[$languageCode])) {
            $this->translatable_attributes[$languageCode] = [];
        }

        $this->translatable_attributes[$languageCode] = array_merge($this->translatable_attributes[$languageCode], $attributes);
    }

}