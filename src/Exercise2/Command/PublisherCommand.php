<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbermejo
 * Date: 13/10/13
 * Time: 11:46
 * To change this template use File | Settings | File Templates.
 */

namespace Exercise2\Command;

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
            ->setName('exercise2:publisher')
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
        $message = new AMQPMessage(
            $data,
            array('delivery_mode' => 2) // Make the message persistent
        );
        $this->channel->queue_declare('task_queue', false, true, false, false);
        $this->channel->basic_publish($message, '', 'task_queue');
        $output->writeln(" [x] Sent $data");
    }
}