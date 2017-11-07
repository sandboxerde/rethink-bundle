<?php

namespace TBoileau\RethinkBundle\Util;

class ClassUtils
{
    public static function getProxyFilename($proxyDir, $proxyNamespace, $className)
    {
        $classNameRelativeToProxyNamespace = substr($className, strlen($proxyNamespace));
        $fileName = str_replace('\\', '', $classNameRelativeToProxyNamespace);
        return $proxyDir . DIRECTORY_SEPARATOR . str_replace("\\","",$className) . '.php';
    }

    public static function originalClass($proxyClass)
    {
        $class = str_replace("__Proxy__\\","",$proxyClass);
        return str_replace(self::getClassNameForProxy(self::getRealNamespace($class)),"",$class);
    }

    public static function proxyClass($class)
    {
        return self::getRealNamespace($class)."\\".self::getClassName($class);
    }

    public static function getClassNameForProxy($class)
    {
        return str_replace('\\', '', $class);
    }

    public static function getFilenameByProxyClass($cacheDir,$fileName)
    {
        return $cacheDir.'/t_boileau_rethink_bundle/proxies/__Proxy__'.$fileName.".php";;
    }

    public static function getRealNamespace($class)
    {
        return substr($class,0, strrpos($class,"\\"));
    }

    public static function getClassName($class)
    {
        return substr($class,strrpos($class,"\\")+1);
    }

    public static function getClassWithNamespace($class)
    {
        return "\\".$class;
    }
}
