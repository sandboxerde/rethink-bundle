<?php

namespace TBoileau\RethinkBundle\Util;

use TBoileau\RethinkBundle\Util\MetadataDriver;
use Symfony\Bundle\TwigBundle\TwigEngine;

class ProxyGenerator
{
    private $cacheDir;

    private $templating;

    public function __construct($cacheDir,TwigEngine $templating)
    {
        $this->cacheDir = $cacheDir;
        $this->templating = $templating;
    }

    public function generate($class,$document)
    {
        if(!is_dir($this->cacheDir.'/t_boileau_rethink_bundle/proxies')){
            mkdir($this->cacheDir.'/t_boileau_rethink_bundle');
            mkdir($this->cacheDir.'/t_boileau_rethink_bundle/proxies');
        }
        $fileName = ClassUtils::getClassNameForProxy($class);
        $cachePath = ClassUtils::getFilenameByProxyClass($this->cacheDir,$fileName);
        file_put_contents($cachePath,
            $this->templating->render('TBoileauRethinkBundle::proxy.html.twig', array(
                'namespace' => ClassUtils::getRealNamespace($class),
                "class"=> ClassUtils::getClassName($class),
                "classWithNamespace"=> ClassUtils::getClassWithNamespace($class),
                "properties"=>$document["properties"]
            ))
        );
    }
}
