<?php
namespace App\Player\Command; 

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Player\Infrastructure\Server;
use App\Player\Application\CommandHandler;
use App\Player\Infrastructure\ServerConfig;

class StartServer extends Command {
    
    protected function configure() 
    {
        $this->setName('app:player:start');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) 
    {
        $commandHandler = new CommandHandler();
        $config = new ServerConfig(
            'localhost', 9090, 10   
        );
        $server = new Server($config, $commandHandler);
        $server->start();
    }
    
}

