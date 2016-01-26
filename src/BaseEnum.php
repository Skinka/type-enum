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
 * Class to implement type of enumerations
 *
 * @package skinka\php\TypeEnum
 */
abstract class BaseEnum
{
    /**
     * The list enumerators is description constants
     *[constant => ["dataField" => "description", ...], ...]
     *
     * @var array
     */
    protected static $data = [];

    /**
     * The selected enumerator value
     *
     * @var null|bool|int|float|string
     */
    private $value;

    /**
     * Available constants class
     * ["class" => ["name" => value, ...], ...]
     *
     * @var array
     */
    private static $constants;

    /**
     * Ready instances
     * ["class" => ["name" => instance, ...], ...]
     *
     * @var array
     */
    private static $instances;

    /**
     * BaseEnum constructor.
     *
     * @param null|bool|int|float|string $value The constant value of the enumerator
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Get the value of the enumerator
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * Get an enumerator instance by the given constant name
     *
     * @param string $method The name of the enumerator (called as method)
     * @param array $args There should be no arguments
     * @return static
     * @throws \InvalidArgumentException On an invalid or unknown name
     * @throws \LogicException Not unique constants value
     */
    final public static function __callStatic($method, array $args)
    {
        return static::getByName($method);
    }

    /**
     * Get an enumerator instance by the constant name
     *
     * @param string $name The constant name
     * @return static
     * @throws \InvalidArgumentException On an invalid or unknown name
     * @throws \LogicException Not unique constants value
     */
    final public static function getByName($name)
    {
        $name = (string)$name;
        $class = get_called_class();
        if (isset(self::$instances[$class][$name])) {
            return self::$instances[$class][$name];
        }
        $const = $class . '::' . $name;
        if (!defined($const)) {
            throw new \InvalidArgumentException($const . ' not defined');
        }
        return self::$instances[$class][$name] = new $class(constant($const));
    }

    /**
     * Get an enumerator instance by the constant value
     *
     * @param null|bool|int|float|string $value The constant value
     * @return static
     * @throws \InvalidArgumentException On an invalid or unknown name
     * @throws \LogicException Not unique constants value
     */
    final public static function getByValue($value)
    {
        $class = get_called_class();
        $constants = array_flip(self::getConstants());
        if (isset($constants[$value])) {
            return self::$instances[$class][$constants[$value]] = new $class($value);
        } else {
            throw new \InvalidArgumentException('"' . $value . '" not defined');
        }
    }

    /**
     * Get all available constants of the called class
     *
     * @return array
     * @throws \LogicException Not unique constants value
     */
    final public static function getConstants()
    {
        return self::detectConstants(get_called_class());
    }

    /**
     * Is the given value part of this enumeration
     *
     * @param static|null|bool|int|float|string $value
     * @return bool
     * @throws \LogicException Not unique constants value
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
     * Detect all available constants by the given class
     *
     * @param string $class
     * @return array
     * @throws \LogicException Not unique constants value
     */
    private static function detectConstants($class)
    {
        if (!isset(self::$constants[$class])) {
            $reflection = new \ReflectionClass($class);
            $constants = $reflection->getConstants();
            $notUnique = [];
            foreach ($constants as $value) {
                $names = array_keys($constants, $value, true);
                if (count($names) > 1) {
                    $notUnique[var_export($value, true)] = $names;
                }
            }
            if (!empty($notUnique)) {
                throw new \LogicException('All possible values needs to be unique. The following are not unique: ' . implode(', ',
                        array_map(function ($names) use ($constants) {
                            return implode('/', $names) . '=' . var_export($constants[$names[0]], true);
                        }, $notUnique)));
            }
            while (($reflection = $reflection->getParentClass()) && $reflection->name !== __CLASS__) {
                $constants = $reflection->getConstants() + $constants;
            }
            self::$constants[$class] = $constants;
        }
        return self::$constants[$class];
    }

    /**
     * Return array data list
     * ["key" => "description", ...]
     *
     * @param string $dataFiled Name dataField description
     * @return array
     */
    final public static function getDataList($dataFiled = 'text')
    {
        $result = [];
        foreach (static::$data as $key => $value) {
            $result[$key] = $value[$dataFiled];
        }
        return $result;
    }

    /**
     * Return array keys on constants
     * [value, ...]
     *
     * @return array
     * @throws \LogicException Not unique constants value
     */
    final public static function getKeys()
    {
        return array_values(self::getConstants());
    }

    /**
     * Get value on dataField selected instance
     *
     * @param string $name The name dataField
     * @param array $arguments There should be no arguments
     * @return string
     * @throws \InvalidArgumentException On an invalid or unknown name
     */
    function __call($name, $arguments)
    {
        if (isset(static::$data[$this->value][$name])) {
            return static::$data[$this->value][$name];
        }
        throw new \InvalidArgumentException('"' . $name . '" not defined');
    }
}