<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbermejo
 * Date: 16/10/13
 * Time: 23:39
 * To change this template use File | Settings | File Templates.
 */

namespace Exercise4\Command;

use Command;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublisherCommand extends Command
{
    public function configure()
    {
        $this
            ->setName('exercise4:publisher')
            ->setDescription('Publisher for exercise4')
            ->addArgument(
                'data',
                InputArgument::REQUIRED,
                'What is the data'
            )
            ->addArgument(
                'level',
                InputArgument::OPTIONAL,
                'What is the level',
                'info'
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $input->getArgument('data');
        $level = $input->getArgument('level');

        $channel = $this->channel;
        $channel->exchange_declare('direct_logs', 'direct', false, false, false);

        $msg = new AMQPMessage($data, array('delivery_mode' => 2));

        $channel->basic_publish($msg, 'direct_logs', $level);

        $output->writeln(" [x] Sent $level:$data");
    }
}