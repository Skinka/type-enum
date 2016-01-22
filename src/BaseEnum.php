<?php
/**
 * Created by Skinka.
 * Site: http://cwdlab.com
 * Email: skink@yandex.ru
 * Date: 10.01.2016 23:59
 */

namespace skinka\php\TypeEnum;

/**
 * Class BaseEnum
 * @package skinka\php\TypeEnum
 */
abstract class BaseEnum
{
    /**
     * @var array
     */
    protected static $data = [];
    /**
     * @var
     */
    private $value;
    /**
     * @var
     */
    private static $constants;
    /**
     * @var
     */
    private static $instances;

    /**
     * BaseEnum constructor.
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * @param $method
     * @param array $args
     * @return static
     */
    final public static function __callStatic($method, array $args)
    {
        return static::getByName($method);
    }

    /**
     * @param $name
     * @return static
     */
    final public static function getByName($name)
    {
        $name  = (string) $name;
        $class = get_called_class();
        if (isset(static::$instances[$class][$name])) {
            return static::$instances[$class][$name];
        }
        $const = $class . '::' . $name;
        if (!defined($const)) {
            throw new \InvalidArgumentException($const . ' not defined');
        }
        return static::$instances[$class][$name] = new $class(constant($const));
    }

    /**
     * @param $value
     * @return static
     */
    final public static function getByValue($value)
    {
        $class = get_called_class();
        $constants = array_flip(static::getConstants());
        if (isset($constants[$value])) {
            return static::$instances[$class][$constants[$value]] = new $class($value);
        } else {
            throw new \InvalidArgumentException('"' . $value . '" not defined');
        }
    }

    /**
     * @return array
     */
    final public static function getConstants()
    {
        return self::detectConstants(get_called_class());
    }

    /**
     * @param $value
     * @return bool
     */
    final public static function has($value)
    {
        if ($value instanceof static && get_class($value) === get_called_class()) {
            return true;
        }
        $class = get_called_class();
        $constants = self::detectConstants($class);
        return in_array($value, $constants, true);
    }

    /**
     * @param $class
     * @return array
     */
    private static function detectConstants($class)
    {
        if (!isset(self::$constants[$class])) {
            $reflection = new \ReflectionClass($class);
            $constants = $reflection->getConstants();
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
            while (($reflection = $reflection->getParentClass()) && $reflection->name !== __CLASS__) {
                $constants = $reflection->getConstants() + $constants;
            }
            self::$constants[$class] = $constants;
        }
        return self::$constants[$class];
    }

    /**
     * Return array data list [$key => $textField, $key => $textField, ...]
     *
     * @param string $textFiled
     * @return array
     */
    final public static function getDataList($textFiled = 'text')
    {
        $result = [];
        foreach (static::$data as $key => $value) {
            $result[$key] = $value[$textFiled];
        }
        return $result;
    }

    /**
     * Return array values on constants [0, 1, ...]
     *
     * @return array
     */
    final public static function getArray()
    {
        return array_values(self::getConstants());
    }

    /**
     * @param $name
     * @param $arguments
     * @return static
     */
    function __call($name, $arguments)
    {
        if (isset(static::$data[$this->value][$name])) {
            return static::$data[$this->value][$name];
        }
        throw new \InvalidArgumentException($name . ' not defined');
    }
}