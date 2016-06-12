<?php
/**
 * Created by Skinka.
 * Site: http://cwdlab.com
 * Email: skink@yandex.ru
 */

namespace skinka\php\TypeEnum\enums;

use skinka\php\TypeEnum\BaseEnum;

/**
 * Class to enumerations of people gender
 *
 * @method static Gender MALE()
 * @method static Gender FEMALE()
 * @method string text()
 * @method string lowerText()
 * @method string gender()
 */
class Gender extends BaseEnum
{
    const MALE = 1;
    const FEMALE = 2;

    public static function getData()
    {
        return [
            self::MALE => [
                'text' => 'Male',
                'lowerText' => 'male',
                'gender' => 'Man',
            ],
            self::FEMALE => [
                'text' => 'Female',
                'lowerText' => 'female',
                'gender' => 'Woman',
            ]
        ];
    }
}