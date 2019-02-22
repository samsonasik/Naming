Naming
======

[![Latest Version](https://img.shields.io/github/release/samsonasik/Naming.svg?style=flat-square)](https://github.com/samsonasik/Naming/releases)
[![Build Status](https://travis-ci.org/samsonasik/Naming.svg?branch=master)](https://travis-ci.org/samsonasik/Naming)
[![Coverage Status](https://coveralls.io/repos/github/samsonasik/Naming/badge.svg?branch=master)](https://coveralls.io/github/samsonasik/Naming?branch=master)
[![PHPStan](https://img.shields.io/badge/style-level%20max-brightgreen.svg?style=flat-square&label=phpstan)](https://github.com/phpstan/phpstan)
[![Downloads](https://img.shields.io/packagist/dt/samsonasik/naming.svg?style=flat-square)](https://packagist.org/packages/samsonasik/naming)

Naming is a library that has filter and validator for people name with multibyte string check support, extends the [`ZF`](https://framework.zend.com/) filter and validator, while it can be used as standalone.

Installation
------------

```sh
composer require samsonasik/naming
```

Filter Flow
-----------

- Strip Tags
- String Trim
- String To Upper first letter in each word with set lower case after that
- Replace double space to single space
- String To Upper after `'` and `-` character if any

Examples:

| Original name    | Filtered name
|------------------|-----------------
| ABduL            | Abdul
| aBDUL m. ikHsan  | Abdul M. Ikhsan
| abdul Malik  I   | Abdul Malik I
| D'lilah          | D'Lilah
| Veli-matti       | Veli-Matti
| äX               | Äx

Validation checks
-----------------

- Allowed characters: letters, hyphens, apostrophe, spaces, full stops.
- Not allowed:
   - special characters
   - single `.` character
   - single `-` character
   - single `'` character
   - consecutive `.` characters
   - consecutive `-` characters
   - consecutive `'` characters
   - full stops not in the last of each word

Usage with zend-form instance:

```php
use Naming\Filter;
use Naming\Validator;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class ContactForm extends Form implements InputFilterProviderInterface
{
    public function init()
    {
        $this->add([
            'type' => Text::class,
            'name' => 'fullname',
            'options' => [
                'label' => 'Full name',
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            [
                'name'     => 'fullname',
                'required' => true,
                'filters' => [
                    [
                        'name' => Filter\Naming::class
                    ],
                ],
                'validators' => [
                    [
                        'name' => Validator\Naming::class,
                    ],
                ],
            ],
        ];
    }
}
```

Using standalone:

```php
use Naming\Filter;
use Naming\Validator;

include 'vendor/autoload.php';

// ... VALID
$filtered = (new Filter\Naming())->filter('Abdul malik ikhsan');
$validator = new Validator\Naming();

echo $filtered; // Abdul Malik Ikhsan
var_dump($validator->isValid($filtered)); // true

// ... INVALID
$filtered = (new Filter\Naming())->filter('Abdul....');
$validator = new Validator\Naming();

echo $filtered; // Abdul....
var_dump($validator->isValid($filtered)); // false
var_dump(\current($validator->getMessages())); /* "Consecutive "."s are not allowed" */
```