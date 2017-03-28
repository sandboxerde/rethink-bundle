<?php

namespace TBoileau\RethinkBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use TBoileau\RethinkBundle\Proxy\Autoloader;
use TBoileau\RethinkBundle\Util\ClassUtils;

class TBoileauRethinkBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        $proxyDir = $this->container->getParameter("kernel.cache_dir")."/t_boileau_rethink_bundle/proxies";
        $proxyNamespace = "__Proxy__";
        $container = &$this->container;
        $proxyGenerator = function ($proxyDir, $proxyNamespace, $class) use (&$container) {
            $originalClassName = Util\ClassUtils::originalClass($class);
            $proxyGenerator = $container->get('t_boileau_rethink.util.proxy_generator');
            $metadataDriver = $container->get('t_boileau_rethink.util.metadata_driver');
            $metadata = $metadataDriver->getDocument($originalClassName);
            $proxyGenerator->generate($originalClassName,$metadata);
            clearstatcache(true,ClassUtils::getProxyFilename($proxyDir, $proxyNamespace, $class));
        };
        Autoloader::register($proxyDir, $proxyNamespace, $proxyGenerator);
    }
}
