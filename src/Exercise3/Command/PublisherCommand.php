<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbermejo
 * Date: 16/10/13
 * Time: 22:52
 * To change this template use File | Settings | File Templates.
 */

namespace Exercise3\Command;

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
            ->setName('exercise3:publisher')
            ->setDescription('Publisher for exercise2')
            ->addArgument(
                'data',
                InputArgument::REQUIRED,
                'What is the data'
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $input->getArgument('data');

        $channel = $this->channel;
        $channel
                ->exchange_declare('logs','fanout', false, false, false)
        ;
        $message = new AMQPMessage(
            $data,
            array('delivery_mode' => 2) // Make the message persistent
        );
        $channel->basic_publish($message, 'logs');
        $output->writeln(" [x] Sent $data");
    }

}