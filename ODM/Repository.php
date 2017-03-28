<?php

namespace TBoileau\RethinkBundle\ODM;

class Repository
{
    private $entityManager;

    private $metadata;

    public function __construct($entityManager,$metadata)
    {
        $this->entityManager = $entityManager;
        $this->metadata = $metadata;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function query(callable $callback = null)
    {
        $table = \r\table($this->metadata["table"]["name"]);
        if($callback !== null) $table = $callback($table);
        return new Statement($this->entityManager,$table->run($this->entityManager->getConnection()),$this->metadata);
    }
}
