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
     * @var array Define the properties that are allowed to be translated on the model.
     */
    protected $translatable = ['*'];

    /**
     * @var array
     */
    private $pendingTranslatableAttributes = [];

    /**
     * @var bool
     */
    protected $translatableUpdated = false;

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

        /*
         * Even if we have set the attribute but not saved the model yet, return it since it is the most actual.
         */
        if (!$this->translatableUpdated && isset($this->pendingTranslatableAttributes[$languageCode][$attribute])) {
            return $this->pendingTranslatableAttributes[$languageCode][$attribute];
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
        foreach ($this->pendingTranslatableAttributes as $language_id => $attributes) {

            $model = $this->obtainTextModel($language_id);
            $model->fill($attributes);

            $model->save();
        }

        $this->translatableUpdated = true;
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
            $languageCode = Language::fallbackLanguage();
        }

        $this->translatableUpdated = false;
        if (!isset($this->pendingTranslatableAttributes[$languageCode])) {
            $this->pendingTranslatableAttributes[$languageCode] = [];
        }

        $this->pendingTranslatableAttributes[$languageCode] = array_merge($this->pendingTranslatableAttributes[$languageCode], $attributes);
    }

}