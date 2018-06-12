<?php

abstract class Model{
    
    protected $db;
    
    function __construct(PDO $db) {
        $this->db = $db;
    }
}

