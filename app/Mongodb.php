<?php
namespace App;

use Config;

class Mongodb
{
    public static $instance;
    
    public $manager;
    public $database="";
    public $collection="";
    
    private function __construct()
    {
        $conn=Config::get('database.connections.'.Config::get('database.default'));
        if ($conn['username']!="") {
            $user=$conn['username'].":".$conn['password']."@";
        } else {
            $user="";
        }
        $this->database=$conn['database'];
        $this->manager=new \MongoDB\Driver\Manager("mongodb://".$user.$conn['host'].":".$conn['port']."/".$conn['database']);
    }
    
    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance=new self;
        }
        return self::$instance;
    }
    
    public function collection($collection)
    {
        $this->collection=$collection;
        return $this;
    }
    
    public function update($filter, $newObj, $updateOptions=[])
    {
        $bulk = new \MongoDB\Driver\BulkWrite();
        $bulk->update($filter, $newObj, $updateOptions);
        
        //$writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 100);
        return $this->manager->executeBulkWrite($this->database.'.'.$this->collection, $bulk);
    }
    
    public function query($filter,$options=[])
    {
        $query = new \MongoDB\Driver\Query($filter, $options);
        return $this->manager->executeQuery($this->database.'.'.$this->collection, $query);
    }
}