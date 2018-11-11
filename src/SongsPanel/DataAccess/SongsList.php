<?php
namespace App\SongsPanel\DataAccess;
use App\SongsPanel\Model\Song;

/**
 * Description of SongsDao
 *
 * @author marek
 */
class SongsList {
    
    const FILE = '../data/songs.json';
    
    private $songs = null;
    
    public function load() {
        $this->getSongs();
    }
   
    public function findAll() : array {
        return $this->getSongs();
    }
    
    public function findById(int $songId) : Song {
        foreach ($this->songs as $song) {
            if ($song->id() == $songId) {
                return $song;
            }
        }
        
        throw new \Exception("Could not find song of id $songId");
    }
    
    public function addSong(Song $song) {
        $this->songs[] = $song;
    }
    
    public function remove(int $songId) {
        $index = $this->findIndexOfSongWithId($songId); 
        unset($this->songs[$index]);
    }
    
    public function findPlaying() {
        foreach ($this->songs as $song) {
            if ($song->isPlaying()) {
                return $song;
            }
        }
        
        return null;
    }
    
    private function findIndexOfSongWithId($songId) {
        foreach ($this->songs as $index => $song) {
            if ($song->id() == $songId) {
                return $index;
            }
        }
        
        throw new \InvalidArgumentException("Cannot find song of id $songId");
    }
    
    public function nextId() : int {
        return count($this->songs) + 1;
    }
    
    public function persist() {
        $songs = [];
        
        foreach ($this->songs as $song) {
            $songs[] = $song->data();
        }
        
        $songsJson = json_encode($songs);
        file_put_contents(self::FILE, $songsJson);
    }
    
    /**
     * 
     * @return array<Song>
     */
    private function getSongs() {
        if ($this->songs == null) { 
            $this->songs = array_map([$this, 'newSong'], $this->loadSongs());
        }
        
        return $this->songs;
    }
    
    private function newSong($songData) {

        return new Song(
            $songData['id'],
            $songData['name'], 
            $songData['url'],
            $songData['status']
        );
    }
    
    private function loadSongs() {
        $songsJson = file_get_contents(self::FILE); 
        return json_decode($songsJson, true);
    }
}
