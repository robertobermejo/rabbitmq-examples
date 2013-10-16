<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbermejo
 * Date: 16/10/13
 * Time: 23:16
 * To change this template use File | Settings | File Templates.
 */

namespace Exercise3\Command;

use Command;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumerCommand extends Command
{

    public function configure()
    {
        $this
            ->setName('exercise3:consumer')
            ->setDescription('Consumer for exercise3')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $channel = $this->channel;
        $channel->exchange_declare('logs', 'fanout', false, false, false);
        list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);
        $channel->queue_bind($queue_name, 'logs');

        $output->writeln(' [*] Waiting for logs. To exit press CTRL+C');

        $callback = function ($msg) use ($output) {
            $output->writeln(" [x] $msg->body");
        };

        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }
    }
}