<?php
/**
 * Created by Skinka.
 * Site: http://cwdlab.com
 * Email: skink@yandex.ru
 */

namespace skinka\php\TypeEnum\enums;

use skinka\php\TypeEnum\BaseEnum;

/**
 * Class to enumerations of published status
 *
 * @method static Published PUBLISHED()
 * @method static Published UNPUBLISHED()
 * @method string text()
 * @method string class()
 */

class Published extends BaseEnum
{
    const PUBLISHED = 1;
    const UNPUBLISHED = 0;

    public static function getData()
    {
        return [
            self::PUBLISHED => [
                'text' => 'Published',
                'class' => 'label label-success label-sm',
            ],
            self::UNPUBLISHED => [
                'text' => 'Un published',
                'class' => 'label label-danger label-sm',
            ]
        ];
    }

    /**
     * Get html block published status from bootstrap style
     *
     * @param $val
     * @return string
     */
    public static function getObjectValue($val)
    {
            return '<span class="' . static::getByValue($val)->class() . '">' . static::getByValue($val)->text() . '</span>';
    }
}