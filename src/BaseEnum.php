<?php
/**
 * Created by Skinka.
 * Site: http://cwdlab.com
 * Email: skink@yandex.ru
 * Date: 10.01.2016 23:59
 */

namespace skinka\php\TypeEnum;

abstract class BaseEnum
{
    protected static $list = [];

    public static function getDropDown()
    {
        $result = [];
        foreach (static::$list as $key => $value) {
            $result[$key] = $value['name'];
        }
        return $result;
    }

    public static function getIn()
    {
        return array_keys(static::$list);
    }

    public static function getStringValue($val)
    {
        if (static::has($val)) {
            return static::$list[$val]['name'];
        } else {
            return '';
        }
    }

    public static function has($val)
    {
        return in_array($val, static::getIn());
    }
}