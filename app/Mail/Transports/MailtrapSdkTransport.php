<?php

namespace App\Mail\Transports;

use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\MessageConverter;

class MailtrapSdkTransport extends AbstractTransport
{
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        parent::__construct();
        $this->apiKey = $apiKey;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $mailtrapEmail = new MailtrapEmail;

        // Map From
        foreach ($email->getFrom() as $address) {
            $mailtrapEmail->from(new Address($address->getAddress(), $address->getName()));
        }

        // Map To
        foreach ($email->getTo() as $address) {
            $mailtrapEmail->to(new Address($address->getAddress(), $address->getName()));
        }

        // Map Cc
        foreach ($email->getCc() as $address) {
            $mailtrapEmail->cc(new Address($address->getAddress(), $address->getName()));
        }

        // Map Bcc
        foreach ($email->getBcc() as $address) {
            $mailtrapEmail->bcc(new Address($address->getAddress(), $address->getName()));
        }

        // Map Reply-To
        foreach ($email->getReplyTo() as $address) {
            $mailtrapEmail->replyTo(new Address($address->getAddress(), $address->getName()));
        }

        $mailtrapEmail->subject($email->getSubject());

        if ($email->getHtmlBody()) {
            $mailtrapEmail->html($email->getHtmlBody());
        }
        if ($email->getTextBody()) {
            $mailtrapEmail->text($email->getTextBody());
        }

        // Send using the Mailtrap SDK
        MailtrapClient::initSendingEmails(apiKey: $this->apiKey)->send($mailtrapEmail);
    }

    public function __toString(): string
    {
        return 'mailtrap';
    }
}
