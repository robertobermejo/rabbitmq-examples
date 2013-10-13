<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbermejo
 * Date: 11/10/13
 * Time: 23:57
 * To change this template use File | Settings | File Templates.
 */

namespace Exercise1\Command;

use Command;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublisherCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('exercise1:publisher')
            ->setDescription('Publisher of exercise 1')
            ->addArgument(
                'msg',
                InputArgument::REQUIRED,
                'What is the message'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $msg = $input->getArgument('msg');
        $channel = $this->channel;
        $channel->queue_declare('hello', false, false, false);
        $amqpMessage = new AMQPMessage($msg);
        $channel->basic_publish($amqpMessage, '', 'hello');

        $output->writeln("[X] Sent '$msg'.");
    }

}