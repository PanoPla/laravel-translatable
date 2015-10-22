# laravel-translatable

**Observation**: This repository is not finished yet, therefore don't use it in your project yet!

This is an easy and simple yet effective model translation laravel package.

Enable today your model classes for multiple languages!

# Examples

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