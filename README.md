<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**  *generated with [DocToc](https://github.com/thlorenz/doctoc)*

- [laravel-translatable](#laravel-translatable)
  - [Examples](#examples)
  - [Setup](#setup)
  - [Using on your classes](#using-on-your-classes)
    - [Main model migration](#main-model-migration)
    - [Main model class](#main-model-class)
    - [Text model class](#text-model-class)
    - [Text model class migration](#text-model-class-migration)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

# laravel-translatable

**Observation**: This repository is not finished yet, therefore don't use it in your project yet!

This is an easy and simple yet effective model translation laravel package.

Enable today your model classes for multiple languages!

## Examples

```php
use panopla\TranslatableTrait;

class User extends Model {
    use TranslatableTrait;
    
    $translatable = ['bio'];
    
}
    
___
    
Language::sessionLanguage('en');

$user->bio = "My English bio";

Language::sessionLanguage('pt');

$user->bio = "Usando Português agora para a minha bio";
    
```

## Setup

Add this following line to your `providers` list

```php
'providers' => [
    panopla\Translatable\TranslatableServiceProvider::class,
]
```

And this for the `aliases` section

```php
'aliases' => [
    'Language'  => panopla\Translatable\Facades\Language::class,
]
```

## Using on your classes

Supposing you have a class named `Product`, and you desire to make its text fields translatable.

For the sake of simplicity, let us assume that we only need a name and price for our model.

### Main model migration

```php
Schema::create('product', function (Blueprint $table) {
            $table->increments('id');

            $table->decimal('price');

            $table->timestamps();
        });
```

### Main model class


We are making usage of the `TranslatableInterface` php interface here in case you'd like
to program to an interface
```php
<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use panopla\Translatable\TranslatableInterface;
use panopla\Translatable\TranslatableTrait;

class Product extends Model implements TranslatableInterface
{
    use TranslatableTrait;

    protected $translatable = ['name'];

}
```
### Text model class

Additionally, you will create a class with the same named suffixed with `Text` on its name.

```php
<?php namespace App;


use Illuminate\Database\Eloquent\Model;

class ProductText extends Model
{

    protected $fillable = ['name'];

}
```

### Text model class migration

```php
public function up()
{
    Schema::create('product_text', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->timestamps();
    });
}

public function down()
{
    Schema::drop('product_text');
}
```