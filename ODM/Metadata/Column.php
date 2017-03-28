<?php

namespace TBoileau\RethinkBundle\ODM\Metadata;

use Doctrine\Common\Annotations\Annotation;
/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Column
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;
}
