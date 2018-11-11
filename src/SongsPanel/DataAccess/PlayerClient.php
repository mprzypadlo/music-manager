<?php

namespace App\SongsPanel\DataAccess;

/**
 * Description of PlayerClient
 *
 * @author marek
 */
class PlayerClient {

    private $host;
    private $port;

    public function __construct() {
        $this->host = 'localhost';
        $this->port = 9090;
    }

    public function play(string $url) {
        $this->sendCommand("PLAY $url");
    }
    
    public function stop() {
        $this->sendCommand('STOP');
    }
    
    public function screenshot() {
        $this->sendCommand('SCREENSHOT');
    }
    
    private function sendCommand($command) {
        $socket = socket_create(AF_INET, SOCK_STREAM, 0);
        socket_connect($socket, $this->host, $this->port);

        $command .= PHP_EOL;

        socket_send($socket, $command, strlen($command), MSG_EOF);
        socket_recv($socket, $buffer, 2048, MSG_WAITALL);
        socket_close($socket);
    }

}
