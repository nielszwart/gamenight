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
    private $em;
    private $mailer;
    private $twig;

    public function __construct(\Twig_Environment $twig, ContainerInterface $container, \Swift_Mailer $mailer)
    {
        $this->doctrine = $container->get('doctrine');
        $this->em = $this->doctrine->getManager();
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
    	$players = $this->doctrine->getRepository(EventPlayer::class)->findBy([
            'event' => $input->getArgument('event'),
            'confirmed' => false,
            'invite_sent' => false,
        ]);
        $output->writeln('Event   : ' . $event->getName());
        $output->writeln('Players : ' . count($players));

        $output->writeln('SENDING INVITES');
    	foreach ($players as $player) {
            $this->setPlayerCode($player);
            $output->write('- Sending to ' . $player->getPlayer()->getEmail() . '...');
            $this->sendInvite($event, $player, $output);
    	}
        $output->writeln('ALL DONE!');
    }

    protected function setPlayerCode(EventPlayer $player)
    {
        $player->setCode();
        $this->em->persist($player);
        $this->em->flush();
    }

    protected function sendInvite(Event $event, EventPlayer $player, $output)
    {
    	$message = (new \Swift_Message('GameNights invites you to ' . $event->getName()))
    	->setFrom('nielszwart@hotmail.com')
    	->setTo($player->getPlayer()->getEmail())
    	->setBody($this->twig->render('email/invite.twig', ['event' => $event, 'player' => $player], 'text/html'))
        ->setContentType('text/html');

        try {
            $this->mailer->send($message);
        } catch (\Exception $e) {
            $output->writeln('Failed to send email (mailhog/mailcatcher is probably not running?)');
            return;
        }

        $player->setInviteSent();
        $this->em->persist($player);
        $this->em->flush();
        $output->writeln(' Done!');
    }
}