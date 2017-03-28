<?php

namespace TBoileau\RethinkBundle\ODM;

use TBoileau\RethinkBundle\Util\MetadataDriver;

class EntityManager
{

    private $connections;

    private $metadataDriver;

    public function __construct($connection, MetadataDriver $metadataDriver)
    {
        $this->connection = $connection;
        $this->metadataDriver = $metadataDriver;
    }

    public function getConnection()
    {
        return \r\connect([
            "host"=>$this->connection["hostname"],
            "port"=>$this->connection["port"],
            "db"=>$this->connection["dbname"]
        ]);
    }

    public function getMetadataDriver()
    {
        return $this->metadataDriver;
    }

    public function getRepository($class)
    {
        $metadata = $this->metadataDriver->getDocument($class);
        if($metadata["table"]["repositoryClass"] != null){
            return new $metadata["table"]["repositoryClass"]($this,$metadata);
        }else{
            return new Repository($this,$metadata);
        }
    }

    public function update($document)
    {
        $metadata = $this->metadataDriver->getDocument(get_class($document));
        $conn = \r\connect([
            "host"=>$this->connection["hostname"],
            "port"=>$this->connection["port"],
            "db"=>$this->connection["dbname"]
        ]);
        $data = [];
        foreach($metadata["properties"]["columns"] as $name=>$column)
        {
            $data[$column["name"]] = $document->__get($name);
        }
        foreach($metadata["properties"]["manyToOne"] as $name=>$column)
        {
            $data[$column["name"]] = $document->__get($name) != null ? $document->__get($name)->getId() : null;
        }
        $result = \r\table($metadata["table"]["name"])->filter(\r\row("id")->eq($document->getId()))->update($data)->run($conn);
        if($result["errors"] == 0 && $result["replaced"] == 1){
            return true;
        }else return false;
    }

    public function insert($document)
    {
        $metadata = $this->metadataDriver->getDocument(get_class($document));
        $conn = \r\connect([
            "host"=>$this->connection["hostname"],
            "port"=>$this->connection["port"],
            "db"=>$this->connection["dbname"]
        ]);
        $data = [];
        foreach($metadata["properties"]["columns"] as $name=>$column)
        {
            $data[$column["name"]] = $document->__get($name);
        }
        foreach($metadata["properties"]["manyToOne"] as $name=>$column)
        {
            $data[$column["name"]] = $document->__get($name) != null ? $document->__get($name)->getId() : null;
        }
        $result = \r\table($metadata["table"]["name"])->insert($data)->run($conn);
        if($result["errors"] == 0 && $result["inserted"] == 1){
            $document->setId($result["generated_keys"][0]);
            return true;
        }else return false;
    }
}
