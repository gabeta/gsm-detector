<?php


namespace Gabeta\GsmDetector;


use Gabeta\GsmDetector\Exceptions\GsmDetectorException;
use Gabeta\GsmDetector\Exceptions\InvalidGsmDetectorMethod;

class GsmDetector
{
    private static $config;

    private static $fixKeyName = "fix";

    private static $mobileKeyName = "mobile";

    private static $mobilePrefixLength = 2;

    private static $fixPrefixLength = 3;

    /**
     * GsmDetector constructor.
     * @param array|null $config
     * @throws GsmDetectorException
     */
    public function __construct(array $config = null)
    {
        if ($config === null) {
            if (self::$config === null) {
              $msg = 'No config provided, and none is global set. Use GsmDetector::setConfig, or instantiate the GsmDetector class with a $config parameter.';
              throw new GsmDetectorException($msg);
            }
        } else {
            self::validateConfig($config);
            self::$config = $config;
        }
    }

    /**
     * @param $methodName
     * @param $arguments
     * @return bool
     * @throws InvalidGsmDetectorMethod
     */
    public function __call($methodName, $arguments)
    {
        $methodName = lcfirst(substr($methodName, 2, (strlen($methodName) - 2)));

        $name = preg_split('/(?=[A-Z])/', $methodName);

        $gsmName = strtolower($name[0]);

        if (count($name) === 1 && array_key_exists($gsmName, self::$config)) {
            return $this->isGsm($gsmName, $arguments[0]);
        }

        if (count($name) === 1 && ($gsmName === self::$mobileKeyName)) {
            return $this->isType($arguments[0], self::$mobileKeyName);
        }

        if (count($name) === 1 && ($gsmName === self::$fixKeyName)) {
            return $this->isType($arguments[0], self::$fixKeyName);
        }

        if (count($name) === 2) {
            $gsmType = strtolower($name[1]);

            if ($this->gsmHasType($gsmName, $gsmType)) {
                return $this->isGsmWithType($gsmName, $gsmType, $arguments[0]);
            }
        }

        throw new InvalidGsmDetectorMethod('Impossible to use '.$methodName.'() method Add new config value for '.$gsmName);
    }

    /**
     * @param $name
     * @param string $value
     * @return bool
     */
    public function isGsm($name, string $value)
    {
        $gsmConfig = self::$config[$name];

        $prefix = call_user_func_array('array_merge', $gsmConfig);

        return $this->hasValue($prefix, $value);
    }

    /**
     * @param $gsm
     * @param $type
     * @param $value
     * @return bool
     */
    public function isGsmWithType($gsm, $type, $value)
    {
        $gsmConfig = self::$config[$gsm];

        $prefix = $gsmConfig[$type];

        return $this->hasValue($prefix, $value);
    }

    /**
     * @param $gsm
     * @param $type
     * @return bool
     * @throws InvalidGsmDetectorMethod
     */
    private function gsmHasType($gsm, $type)
    {
        $gsmConfig = self::$config[$gsm];

        if (! array_key_exists($type, $gsmConfig)) {
            throw new InvalidGsmDetectorMethod('You have not declare '.$type.' value for '.$gsm);
        }

        return true;
    }

    /**
     * @param $value
     * @param $type
     * @return bool
     */
    public function isType($value, $type)
    {
        $prefix = [];


        foreach (self::$config as $config) {
            $prefix = array_merge($prefix, $config[$type]);
        }

        return $this->hasValue($prefix, $value);
    }

    /**
     * @param $value
     * @return int|string|null
     */
    public function getGsmName($value)
    {
        foreach (self::$config as $key => $config) {
            $prefix = call_user_func_array('array_merge', $config);

            if ($this->hasValue($prefix, $value)) {
                return $key;
            }
        }

        return null;
    }

    /**
     * @param array $config
     */
    public static function setConfig(array $config)
    {
        self::validateConfig($config);

        self::$config = $config;
    }

    public static function setMobilePrefixLength(int $length)
    {
        self::$mobilePrefixLength = $length;
    }

    public static function setFixPrefixLength(int $length)
    {
        self::$fixPrefixLength = $length;
    }

    /**
     * @param array $config
     * @return bool
     */
    private static function validateConfig(array $config)
    {
        if (!count($config)) {
            throw new \InvalidArgumentException('config must not be empty array.');
        }

        self::validateConfigFormat($config);

        return true;
    }

    /**
     * @param $config
     */
    private static function validateConfigFormat($config)
    {
        foreach ($config as $key => $conf) {
            if (! (array_key_exists(self::$fixKeyName, $conf) || array_key_exists(self::$fixKeyName, $conf))) {
                throw new \InvalidArgumentException($key.' must have a key '.self::$fixKeyName.' or '.self::$mobileKeyName);
            }

            if (array_key_exists(self::$fixKeyName, $conf) && ! is_array($conf[self::$fixKeyName])) {
                throw new \InvalidArgumentException($key.' '.self::$fixKeyName.' values must be array.');
            }

            if (array_key_exists(self::$mobileKeyName, $conf) && ! is_array($conf[self::$mobileKeyName])) {
                throw new \InvalidArgumentException($key.' '.self::$mobileKeyName.' values must be array.');
            }
        }
    }

    /**
     * @param $value
     * @return false|string
     */
    private function getNumberPrefix($value, $prefixLength)
    {
        return substr($value, 0, $prefixLength);
    }

    private function hasValue($prefix, $value)
    {
        return in_array($this->getNumberPrefix($value, self::$mobilePrefixLength), $prefix) ||
            in_array($this->getNumberPrefix($value, self::$fixPrefixLength), $prefix);
    }
}
