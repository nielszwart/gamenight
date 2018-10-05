<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Entity\Event;
use App\Entity\EventPlayer;

class SendInviteCommand extends Command
{
    private $doctrine;
    private $mailer;
    private $twig;

    public function __construct(\Twig_Environment $twig, ContainerInterface $container, \Swift_Mailer $mailer)
    {
        $this->doctrine = $container->get('doctrine');
        $this->twig = $twig;
        $this->mailer = $mailer;
        parent::__construct();
    }

    protected function configure()
    {
        $this
        ->setName('event:send-invite')
        ->setDescription('Sends invites for given event.')
        ->addArgument('event', InputArgument::REQUIRED, 'The id of the event.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('GETTING EVENT AND PLAYERS');
    	$event = $this->doctrine->getRepository(Event::class)->find($input->getArgument('event'));
    	$players = $this->doctrine->getRepository(EventPlayer::class)->findBy(['event' => $input->getArgument('event')]);
        $output->writeln('Event   : ' . $event->getName());
        $output->writeln('Players : ' . count($players));

        $output->writeln('SENDING INVITES');
    	foreach ($players as $player) {
            $output->write('- Sending to ' . $player->getPlayer()->getEmail() . '...');
            $this->sendInvite($event, $player);
            $output->writeln(' Done!');
    	}
        $output->writeln('ALL DONE!');
    }

    protected function sendInvite(Event $event, EventPlayer $player)
    {
    	$message = (new \Swift_Message('GameNights invites you to ' . $event->getName()))
    	->setFrom('nielszwart@hotmail.com')
    	->setTo($player->getPlayer()->getEmail())
    	->setBody($this->twig->render('email/invite.twig', ['event' => $event, 'player' => $player], 'text/html'));

    	$this->mailer->send($message);
    }
}