<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbermejo
 * Date: 16/10/13
 * Time: 23:47
 * To change this template use File | Settings | File Templates.
 */

namespace Exercise4\Command;

use Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumerCommand extends Command
{
    public function configure()
    {
        $this
            ->setName('exercise4:consumer')
            ->setDescription('Publisher for exercise4')
            ->addArgument(
                'levels',
                InputArgument::OPTIONAL,
                'What is the level',
                'info'
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $levels = explode(',', $input->getArgument('levels'));
        $channel = $this->channel;

        $channel->exchange_declare('direct_logs', 'direct', false, false, false);
        list($queue_name, , ) = $channel->queue_declare("", false, false, true, false);

        foreach($levels as $level) {
            $channel->queue_bind($queue_name, 'direct_logs', $level);
        }

        $output->writeln(' [*] Waiting for logs. To exit press CTRL+C');

        $calback = function ($msg) use ($output) {
            $output->writeln(' [x] '.$msg->delivery_info['routing_key']. ':'. $msg->body);
        };
        $channel->basic_consume($queue_name, '', false, true, false, false, $calback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }
    }
}