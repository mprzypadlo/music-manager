<?php
namespace App\Player\Application;

use Facebook\WebDriver\Remote\RemoteWebDriver;

/**
 * Description of CommandHandler
 *
 * @author marek
 */
class CommandHandler {
    
    /**
     *
     * @var RemoteWebDriver
     */
    private $driver; 
    
    public function __construct() {
        $this->driver = RemoteWebDriver::create(
            'http://localhost:4444/wd/hub',
            \Facebook\WebDriver\Remote\DesiredCapabilities::chrome()
        );
    }
    
    public function handleCommand(string $commandString) : string{;
        $commandTokens = explode(" ", $commandString);
        $command = trim($commandTokens[0]);
        
        if (strtoupper($command) == 'PLAY') {
            $this->playUrl($commandTokens[1]);
            return "OK";
        } else if (strtoupper($command) == 'STOP') {
            $this->driver->get('https://www.youtube.com');
        } else if (strtoupper($command) == 'SCREENSHOT') {
            $this->takeScreenshot();
        }
        
        return "UNABLE TO PLAY";
    }
    
    private function playUrl($url) {
        $this->driver->get($url);
    }
    
    private function takeScreenshot() {
        $image = __DIR__.'/../../../public/screenshots/current.png';
        $this->driver->takeScreenshot($image);
    }
    
}
