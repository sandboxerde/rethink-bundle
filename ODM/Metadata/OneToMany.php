<?php

namespace TBoileau\RethinkBundle\ODM\Metadata;

use Doctrine\Common\Annotations\Annotation;
/**
 * @Annotation
 * @Target("PROPERTY")
 */
class OneToMany
{
    /**
     * @var string
     */
    public $target;

    /**
     * @var string
     */
    public $mappedBy;
}
