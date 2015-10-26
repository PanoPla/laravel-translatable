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

}