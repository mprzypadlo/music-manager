<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Player\Infrastructure;

/**
 * Description of ServerConfig
 *
 * @author marek
 */
class ServerConfig {
    
    private $host; 
    
    private $port; 
    
    private $maxQueue; 
    
    function __construct($host, $port, $maxQueue) {
        $this->host = $host;
        $this->port = $port;
        $this->maxQueue = $maxQueue;
    }
    
    public function host() : string {
        return $this->host;
    }
    
    public function port() : int {
        return $this->port;
    }
    
    public function maxQueue() : int {
        return $this->maxQueue;
    }
    

}
