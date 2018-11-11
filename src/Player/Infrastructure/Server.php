<?php

namespace App\Player\Infrastructure;
use App\Player\Application\CommandHandler;

/**
 * Description of Server
 *
 * @author marek
 */
class Server {

    private $config;
    private $handler;

    public function __construct(
        ServerConfig $config,
        CommandHandler $handler
    ) {
        $this->config = $config;
        $this->handler = $handler;        
    }

    public function start() {
        $socket = $this->createSocket();

        while (true) {
            $this->acceptConnection($socket);            
        }
    }

    private function acceptConnection($socket) {
        $msgSocket = socket_accept($socket);

        if ($msgSocket == false) {
            throw new \RuntimeException("Could not accept connection");
        }

        $buffer = socket_read($msgSocket, 2048, PHP_NORMAL_READ);

        if (!$buffer) {
            return;
        }

        $this->handler->handleCommand($buffer);

        socket_write($msgSocket, "OK");
        socket_close($msgSocket);

        var_dump($buffer);
    }

    private function createSocket() {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_bind($socket, $this->config->host(), $this->config->port());
        socket_listen($socket, $this->config->maxQueue());

        return $socket;
    }

    private function throwExceptionOnSocketError($socket) {
        if ($socket == null) {
            $message = socket_strerror(socket_last_error());
            throw new \RuntimeException($message);
        }
    }

}
