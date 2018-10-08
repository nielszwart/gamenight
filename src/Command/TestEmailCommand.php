<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestEmailCommand extends Command
{
    private $mailer;

    public function __construct( \Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        parent::__construct();
    }

    protected function configure()
    {
        $this
        ->setName('test-email')
        ->setDescription('Tries to send a tiny email to test email server.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('Sending test email... ');
        $this->sendEmail();
        $output->writeln('Done!');
    }

    protected function sendEmail()
    {
    	$message = (new \Swift_Message('Testing email server'))
    	->setFrom('nielszwart@hotmail.com')
    	->setTo('niels.bla@gmail.com')
    	->setBody('test')
        ->setContentType('text/plain');

        $this->mailer->send($message);
    }
}