<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class GmailService {
    public function __construct(private MailerInterface $mailer) {}
    public function sendMail(string $subject, string $receiver) {

        $email=(new Email())
              ->to($receiver)
              ->from("mohamedhadji603@gmail.com")
              ->subject($subject)
              ->html('<h1>What is up?</h1>');
        $this->mailer->send($email);
    }
}