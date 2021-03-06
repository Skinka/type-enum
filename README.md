# PHP type Enum implementation
It's an abstract class that needs to be extended to use  enumeration.

[What is an Enumeration?](https://en.wikipedia.org/wiki/Enumerated_type)
#Install
Add `skinka/type-enum` to the project's composer.json dependencies and run php composer.phar install

#Usage
##Create enum

```php
<?php

namespace skinka\php\TypeEnum\enums;

use skinka\php\TypeEnum\BaseEnum;

/**
 * Class to enumerations of YES or NO status
 *
 * @method static YesNo YES()
 * @method static YesNo NO()
 * @method string text()
 */

class YesNo extends BaseEnum
{
    const YES = 1;
    const NO = 0;

    public static function getData() {
        return [
            self::YES => [
                'text' => 'Yes',
            ],
            self::NO => [
                'text' => 'No',
            ]
        ];
    }
}
```

##Use enum example

```php
YesNo::getDataList(); //[0 => 'No', 1 => 'Yes']

YesNo::getKeys(); //[0,1]

YesNo::NO(); //0

YesNo::YES()->text(); //Yes

YesNo::YES()->getValue(); //1

YesNo::YES()->getArray(); //['text' => 'Yes']

YesNo::getByValue(0)->text(); //No

YesNo::getByName('YES'); //1
```