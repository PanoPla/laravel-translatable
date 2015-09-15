<?php

namespace panopla\Translatable;

interface TranslatableInterface
{

    function getTranslatableAttribute();

    function setTranslatableAttribute();

    function processPendingAttributes();
}