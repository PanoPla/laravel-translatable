<?php

namespace panopla\Translatable;

interface TranslatableInterface
{

    function getTranslatableAttribute($attribute, $languageCode = null);

    function setTranslatableAttribute($attribute, $value, $languageCode = null);

    function processPendingAttributes();
}