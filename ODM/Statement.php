<?php

namespace TBoileau\RethinkBundle\ODM;

use \r\Cursor;
use TBoileau\RethinkBundle\Util\ClassUtils;

class Statement
{
    private $entityManager;

    private $query;

    private $metadata;

    public function __construct($entityManager, $query, $metadata)
    {
        $this->entityManager = $entityManager;
        $this->query = $query;
        $this->metadata = $metadata;
    }

    public function hydrate($data,$class)
    {
        $metadata = $this->entityManager->getMetadataDriver()->getDocument($class);
        $proxyClass = ClassUtils::proxyClass($class);
        $document = new $proxyClass($this->entityManager,$metadata);
        $document->setId($data["id"]);
        foreach($this->metadata["properties"]["columns"] as $name=>$columnMetadata)
        {
            $field = ucfirst($name);
            $document->{"set".$field}($data[$columnMetadata["name"]]);
        }
        foreach($this->metadata["properties"]["manyToOne"] as $name=>$columnMetadata)
        {
            $field = ucfirst($name);
            $document->{"set".$field}($data[$columnMetadata["name"]]);
        }
        return $document;
    }

    public function getSingleResult()
    {
        if($this->query instanceof \r\Cursor){
            $data = $this->query->toArray();
            if(count($data) == 0){
                return null;
            }else{
                return $this->hydrate($data[0],$this->metadata["class"]);
            }
        }else{
            return $this->hydrate($this->query,$this->metadata["class"]);
        }
    }

    public function getSingleScalarResult()
    {
        if($this->query instanceof \r\Cursor){
            $data = $this->query->toArray();
            if(count($data) == 0){
                return null;
            }else{
                return $data[0];
            }
        }else{
            return $this->query;
        }
    }

    public function getResults()
    {
        $data = $this->query->toArray();
        if(count($data) == 0){
            return array();
        }else{
            $array = [];
            foreach($data as $d)
            {
                $array[] = $this->hydrate($d,$this->metadata["class"]);
            }
            return $array;
        }
    }

    public function getScalarResults()
    {
        return $this->query->toArray();
    }
}
