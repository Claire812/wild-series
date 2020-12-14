<?php


namespace App\Service;


class Mailer
{
// ...
    private $adminEmail;

    public function __construct(string $adminEmail)
    {
        // ...
        $this->adminEmail = $adminEmail;
    }

    public function sendMail(): bool
    {
        // ...
    }
}
