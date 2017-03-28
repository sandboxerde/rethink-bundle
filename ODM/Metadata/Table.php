<?php

namespace TBoileau\RethinkBundle\ODM\Metadata;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Table
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $repositoryClass;
}
