<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbermejo
 * Date: 12/10/13
 * Time: 20:46
 * To change this template use File | Settings | File Templates.
 */

namespace Exercise1\Command;

use Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ConsumerCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('exercise1:consumer')
            ->setDescription('Consumer of exercise 1')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $channel = $this->channel;
        $channel->queue_declare('hello', false, false, false);
        $output->writeln(' [*] Waiting for messages. To exit press CTRL+C');
        $callback = function ($msg) use ($output) {
            $output->writeln(" [x] Received ' $msg->body'");
        };
        $channel->basic_consume('hello', '', false, true, false, false, $callback);
        while(count($channel->callbacks)) {
            $channel->wait();
        }
    }
}