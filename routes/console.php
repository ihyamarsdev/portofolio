<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('send-mail', function () {
    $apiKey = env('MAILTRAP_API_TOKEN') ?: '<YOUR_API_TOKEN>';
    $fromAddress = config('mail.from.address') ?: env('MAIL_FROM_ADDRESS', 'hello@demomailtrap.co');
    $fromName = config('mail.from.name') ?: env('MAIL_FROM_NAME', 'Mailtrap Test');
    
    $email = (new MailtrapEmail())
        ->from(new Address($fromAddress, $fromName))
        ->to(new Address('ihyamars@gmail.com'))
        ->subject('You are awesome!')
        ->category('Integration Test')
        ->text('Congrats for sending test email with Mailtrap!')
    ;

    $response = MailtrapClient::initSendingEmails(
        apiKey: $apiKey
    )->send($email);

    var_dump(ResponseHelper::toArray($response));
})->purpose('Send Mail');

