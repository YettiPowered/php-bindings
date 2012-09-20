<?php

namespace Yetti\API;

/**
 * Class loader
 *
 * @author Chris Buckley <chris@cmbuckley.co.uk>
 * @copyright Copyright (c) 2011-2012, Yetti Ltd.
 * @package yetti-api
 */

class Autoloader
{
    /**
     * Autoload class files
     *
     * @param string $className
     * @return void
     */
    public static function autoload($className)
    {
        if (substr($className, 0, 9) == 'Yetti\API') {
            $className = substr($className, 10);
            $classPath = '';

            foreach (explode('_', $className) as $component) {
                $classPath .= '/' . ucfirst($component);
            }

            $classPath = realpath(dirname(__FILE__)) . $classPath . '.php';

            if (file_exists($classPath)) {
                include $classPath;
            }
        }
    }

    /**
     * Register this class as the autoloader
     *
     * @return void
     */
    public static function register()
    {
        spl_autoload_register(__NAMESPACE__ . '\\Autoloader::autoload', true, true);
    }
}
