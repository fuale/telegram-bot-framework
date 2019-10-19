<?php declare(strict_types = 1);

namespace App\Core;

use Cekta\DI\Container;
use Cekta\DI\Provider\Autowiring;
use Cekta\DI\Provider\KeyValue;

/**
 * Class Application
 * run the application
 *
 * @package App\Core
 */
class Application
{

    /** @var Container */
    public static $container;

    public function __construct()
    {
        $require = static function ($file) {
            $cp = \dirname(__DIR__) . "/config/$file.php";
            return file_exists($cp) ? require $cp : [];
        };

        /**
         * Order is important
         * 1. System config
         * 2. Development config
         * 3. Production config
         * 4. Autowire if not defined
         */
        $providers[] = new KeyValue($require('config-sys'));
        $providers[] = new KeyValue($require('config-dev'));
        $providers[] = new KeyValue($require('config-prod'));
        $providers[] = new Autowiring();
        static::$container = new Container(...$providers);
    }

    /**
     * @return \App\Core\Runner
     */
    public function getRunner(): Runner
    {
        return static::$container->get(Runner::class);
    }

}