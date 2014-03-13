<?php

namespace User;

use Zend\Loader\AutoloaderFactory;
use Doctrine\Common\DataFixtures\Loader;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use RuntimeException;


use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

/**

 * Test bootstrap, for setting up autoloading
 */
class Bootstrap
{

    protected static $serviceManager;
    protected static $config;
    protected static $testDBClasses = false;
    protected static $fixture;

    public static function init()
    {
        $zf2ModulePaths = array(dirname(dirname(__DIR__)));
        if (($path = static::findParentPath('vendor'))) {
            $zf2ModulesPaths[] = $path;
        }
        if (($path = static::findParentPath('module')) !== $zf2ModulePaths[0]) {
            $zf2ModulesPaths[] = $path;
        }

        static::initAutoLoader();

        $pathDir = explode('module', getcwd())[0];
        $pathProject = substr($pathDir, 0, strlen($pathDir) - 1);

        // use ModuleManager to load this module and it's dependencies
        static::$config = array(
            'output_buffering' => false, // required for testing sessions
            'modules' => array(
                'DoctrineModule',
                'DoctrineORMModule',
                'DoctrineDataFixtureModule',
                'Base',
                'Front',
                'User',
                'Local'
            ),
            'module_listener_options' => array(
                'config_glob_paths' => array($pathProject . '/config/autoload/{,*.}{testing}.php'),
                'module_paths' => $zf2ModulePaths
            ),
        );

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', static::$config);
        $serviceManager->get('ModuleManager')->loadModules();
        static::$serviceManager = $serviceManager;
        
        /*
        // Adding Fixtures
        $loader = new Loader();
        $loader->loadFromDirectory(__DIR__ . '/../src/User/Fixture');
        
        
        $purger = new ORMPurger();
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());
        
        $executor->execute($loader->getFixtures(), true);
        */
    }

    public static function getConfig()
    {
        return static::$config;
    }

    public static function chroot()
    {
        $rootPath = dirname(static::findParentPath('module'));
        chdir($rootPath);
    }

    public static function getServiceManager()
    {
        return static::$serviceManager;
    }

    public static function getTestDBClasses()
    {
        return static::$testDBClasses;
    }

    protected static function initAutoloader()
    {
        $vendorPath = static::findParentPath('vendor');

        $zf2Path = getenv('ZF2_PATH');
        if (!$zf2Path) {
            if (defined('ZF2_PATH')) {
                $zf2Path = ZF2_PATH;
            } elseif (is_dir($vendorPath . '/ZF2/library')) {
                $zf2Path = $vendorPath . '/ZF2/library';
            } elseif (is_dir($vendorPath . '/zendframework/zendframework/library')) {
                $zf2Path = $vendorPath . '/zendframework/zendframework/library';
            }
        }

        if (!$zf2Path) {
            throw new RuntimeException(
            'Unable to load ZF2. Run php composer.phar install or '
            . ' define a ZF2_PATH environment variable.'
            );
        }

        if (file_exists($vendorPath . '/autoload.php')) {
            include $vendorPath . '/autoload.php';
        }

        include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
        AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader' => array(
                'autoregister_zf' => true,
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/'. __NAMESPACE__,
                ),
            ),
            'Zend\Loader\ClassMapAutoloader' => array(
                'application' => __DIR__ . '/autoload_classmap.php',
            ),
        ));
    }

    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }

        return $dir . '/' . $path;
    }

}

Bootstrap::init();
Bootstrap::chroot();
