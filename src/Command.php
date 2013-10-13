<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbermejo
 * Date: 12/10/13
 * Time: 19:18
 * To change this template use File | Settings | File Templates.
 */

use Symfony\Component\Console\Command\Command  as BaseCommand;
use PhpAmqpLib\Connection\AMQPConnection;

class Command extends BaseCommand
{
    protected $connection;
    protected $channel;

    public function __construct($name = null)
    {
        parent::__construct($name);
        $connection = new AMQPConnection('localhost', 5672, 'guest', 'guest', '/');
        $this->connection = $connection;
        $this->channel = $connection->channel();
    }
    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}