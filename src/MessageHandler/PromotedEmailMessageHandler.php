<?php

namespace App\MessageHandler;

use App\Entity\Album;
use App\Message\PromotedEmailMessage;
use App\Repository\AlbumRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class PromotedEmailMessageHandler implements MessageHandlerInterface
{
    private MailerInterface $mailer;
    private AlbumRepository $albumRepository;
    private $emailTo;

    public function __construct(MailerInterface $mailer, AlbumRepository $albumRepository, $emailTo)
    {
        $this->mailer = $mailer;
        $this->albumRepository = $albumRepository;
        $this->emailTo = $emailTo;
    }

    public function __invoke(PromotedEmailMessage $message)
    {

        /** @var Album $album */
        $album = $this->albumRepository->findOneBy(['id' => $message->getId()]);
        $email = (new Email())
            ->from('bak1990@o2.pl')
            ->to($this->getEmailTo())
            ->subject("New promoted album!!!!")
            ->html("<p>".sprintf("Hi! Awesome promoted album %s is now avaiable!!!", $album->getTitle())."</p>");

        $this->mailer->send($email);
    }

    public function getEmailTo()
    {
        return $this->emailTo;
    }
}
