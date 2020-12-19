<?php


namespace Gabeta\GsmDetector;


use Gabeta\GsmDetector\Exceptions\GsmDetectorException;
use Gabeta\GsmDetector\Exceptions\InvalidGsmDetectorMethod;

class GsmDetector
{
    /**
     * @var array
     */
    private static $config;

    private static $fixKeyName = "fix";

    private static $mobileKeyName = "mobile";

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

    public function __call($methodName, $arguments)
    {
        $name = lcfirst(substr($methodName, 2, (strlen($methodName) - 2)));

        $name = preg_split('/(?=[A-Z])/', $name);

        $gsmName = strtolower($name[0]);

        if (count($name) === 1 && array_key_exists($gsmName, self::$config)) {
            return $this->isGsm($gsmName, $arguments[0]);
        }

        $gsmType = strtolower($name[1]);

        if ($this->gsmHasType($gsmName, $gsmType)) {
            return $this->isGsmWithType($gsmName, $gsmType, $arguments[0]);
        }

        throw new InvalidGsmDetectorMethod('Impossible to use '.$name.'() method Add new config value for '.$gsmName);
    }

    public function isGsm($name, string $value)
    {
        $gsmConfig = self::$config[$name];

        $prefix = call_user_func_array('array_merge', $gsmConfig);

        $valuePrefix = substr($value, 0, 2);

        return in_array($valuePrefix, $prefix);
    }

    public function isGsmWithType($gsm, $type, $value)
    {
        $gsmConfig = self::$config[$gsm];

        $typePrefix = $gsmConfig[$type];

        $valuePrefix = substr($value, 0, 2);

        return in_array($valuePrefix, $typePrefix);
    }

    private function gsmHasType($gsm, $type)
    {
        $gsmConfig = self::$config[$gsm];

        if (! array_key_exists($type, $gsmConfig)) {
            throw new InvalidGsmDetectorMethod('You have not declare '.$type.' value for '.$gsm);
        }

        return true;
    }

    private static function setConfig(array $config)
    {
        self::validateConfig($config);

        self::$config = $config;
    }

    private static function validateConfig(array $config)
    {
        if (!count($config)) {
            throw new \InvalidArgumentException('config must not be empty array.');
        }

        self::validateConfigFormat($config);

        return true;
    }

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
}
