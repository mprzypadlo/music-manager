<?php
namespace App\SongsPanel\Model;

/**
 * Description of Song
 *
 * @author marek
 */
class Song {
    
    private $id; 
    
    private $url; 
    
    private $status;
    
    public function __construct(
        int $id, 
        string $name,
        string $url,
        string $status
    ) { 
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
        $this->status = $status;
    }
    
    public function id() : int {
        return $this->id;
    }
    
    public function url() : string {
        return $this->url;
    }
    
    public function status() : string {
        return $this->status;
    }
    
    public function play() {
        $this->status = 'on';
    }
    
    public function stop() {
        $this->status = 'off';
    }
    
    public function data() {
        return [
            'id' => $this->id, 
            'name' => $this->name, 
            'url' => $this->url,
            'status' => $this->status
        ];
    }
    
    public function isPlaying() : bool {
        return $this->status == 'on';
    }
   
}
