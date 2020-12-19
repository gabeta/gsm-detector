<?php


namespace Gabeta\GsmDetector;


use Gabeta\GsmDetector\Exceptions\GsmDetectorException;

class GsmDetector
{
    /**
     * @var array
     */
    private static $config;

    private $instanceConfig;

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
            $this->instanceConfig = $config;
        }
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
