<?php
/**
 * Created by Skinka.
 * Site: http://cwdlab.com
 * Email: skink@yandex.ru
 */

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

    public static function getData()
    {
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