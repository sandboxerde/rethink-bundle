<?php

namespace TBoileau\RethinkBundle\Proxy;

use TBoileau\RethinkBundle\Util\ClassUtils;

class Autoloader
{

    public static function register($proxyDir, $proxyNamespace, $notFoundCallback = null)
    {
        $proxyNamespace = ltrim($proxyNamespace, '\\');

        $autoloader = function ($className) use ($proxyDir, $proxyNamespace, $notFoundCallback) {
            if (0 === strpos($className, $proxyNamespace)) {
                $file = ClassUtils::getProxyFilename($proxyDir, $proxyNamespace, $className);
                if ($notFoundCallback && ! file_exists($file)) {
                    call_user_func($notFoundCallback, $proxyDir, $proxyNamespace, $className);
                }
                require $file;
            }
        };

        spl_autoload_register($autoloader);

        return $autoloader;
    }
}
