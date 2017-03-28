<?php

namespace TBoileau\RethinkBundle\ODM\Metadata;

use Doctrine\Common\Annotations\Annotation;
/**
 * @Annotation
 * @Target("PROPERTY")
 */
class ManyToOne
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $target;

    /**
     * @var string
     */
    public $inversedBy;
}
