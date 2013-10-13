<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbermejo
 * Date: 13/10/13
 * Time: 11:44
 * To change this template use File | Settings | File Templates.
 */

namespace Exercise2\Command;

use Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumerCommand extends Command
{
    public function configure()
    {
        $this
            ->setName('exercise2:consumer')
            ->setDescription('Consumer for exercise2')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $callback = function ($msg) use ($output) {
            $output->write(" [x] Received  $msg->body.");
            sleep(substr_count($msg->body, '.'));
            $output->writeln(" Done.");
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };
        $channel = $this->channel;
        $channel->queue_declare('task_queue', false, true, false, false);
        $channel->basic_qos(null, 1, null);
        $channel->basic_consume('task_queue', '', false, false, false, false, $callback);
        while(count($channel->callbacks)) {
            $channel->wait();
        }
    }
}