<?php

namespace panopla\Translatable;

use Illuminate\Database\Eloquent\Model;
use panopla\Translatable\Exceptions\NotEloquentModelException;
use panopla\Translatable\Exceptions\TranslatableModelNonExistentException;

/**
 * Class TranslatableTrait
 * @package panopla\Translatable
 */
trait TranslatableTrait
{

    /**
     * @var array
     */
    protected $translatable_attributes = [];

    /**
     * @var bool
     */
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
     * @param $attribute
     * @param $languageCode
     * @return string
     */
    protected function getTranslatableAttribute($attribute, $languageCode)
    {

        if (!$this->translatable_updated && isset($this->translatable_attributes[$languageCode][$attribute])) {
            return $this->translatable_attributes[$languageCode][$attribute];
        }

        $model = $this->obtainTextModel($languageCode);

        return $model->$attribute;

    }

    /**
     * @param $attribute
     * @param null $value
     * @param $languageCode
     */
    protected function setTranslatableAttribute($attribute, $value, $languageCode)
    {
        if (is_array($attribute)) {
            $this->appendTranslatableAttributes($attribute, $value);
        } else {
            $this->appendTranslatableAttributes([$attribute => $value], $languageCode);
        }

    }

    /**
     * @throws NotEloquentModelException
     * @throws TranslatableModelNonExistentException
     */
    protected function processPendingAttributes()
    {
        foreach ($this->translatable_attributes as $language_id => $attributes) {

            $model = $this->obtainTextModel($language_id);
            $model->fill($attributes);

            $model->save();
        }

        $this->translatable_updated = true;
    }

    /**
     * @param $languageCode
     * @return Model
     * @throws NotEloquentModelException
     * @throws TranslatableModelNonExistentException
     */
    private function obtainTextModel($languageCode)
    {
        $reflectionClass = new \ReflectionClass($this);

        if (!$reflectionClass->isSubclassOf(Model::class)) {
            throw new NotEloquentModelException;
        }

        $sTranslatableClassName = $reflectionClass->getName();
        $sTranslatableClassName .= 'Text';

        if (!class_exists($sTranslatableClassName)) {
            throw new TranslatableModelNonExistentException;
        }

        //Yet a model has many translations for different languages,
        //there is only one line for each language ( or so it should be :) )
        $result = $this->hasMany($sTranslatableClassName)->firstOrNew(['language_id' => $languageCode]);

        return $result;
    }

    /**
     * @param array $attributes
     * @param $languageCode
     */
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