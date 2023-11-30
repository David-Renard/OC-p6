<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendMail
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function send(
        string $from,
        string $sendTo,
        string $subject,
        string $template,
        array  $context,
    ): void
    {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($sendTo)
            ->subject($subject)
            ->htmlTemplate("email/$template.html.twig")
            ->context($context);

        $this->mailer->send($email);
    }
}