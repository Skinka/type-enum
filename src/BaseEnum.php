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
    private $value;
    private static $constants;

    /**
     * BaseEnum constructor.
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->getName();
    }

    final public function getName()
    {
        return array_search($this->value, self::detectConstants(get_called_class()), true);
    }

    final public static function getConstants()
    {
        return self::detectConstants(get_called_class());
    }

    final public static function has($value)
    {
        if ($value instanceof static && get_class($value) === get_called_class()) {
            return true;
        }
        $class = get_called_class();
        $constants = self::detectConstants($class);
        return in_array($value, $constants, true);
    }

    private static function detectConstants($class)
    {
        if (!isset(self::$constants[$class])) {
            $reflection = new \ReflectionClass($class);
            $constants = $reflection->getConstants();
            // values needs to be unique
            $ambiguous = array();
            foreach ($constants as $value) {
                $names = array_keys($constants, $value, true);
                if (count($names) > 1) {
                    $ambiguous[var_export($value, true)] = $names;
                }
            }
            if (!empty($ambiguous)) {
                throw new \LogicException('All possible values needs to be unique. The following are ambiguous: ' . implode(', ',
                        array_map(function ($names) use ($constants) {
                            return implode('/', $names) . '=' . var_export($constants[$names[0]], true);
                        }, $ambiguous)));
            }
            // This is required to make sure that constants of base classes will be the first
            while (($reflection = $reflection->getParentClass()) && $reflection->name !== __CLASS__) {
                $constants = $reflection->getConstants() + $constants;
            }
            self::$constants[$class] = $constants;
        }
        return self::$constants[$class];
    }

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
}