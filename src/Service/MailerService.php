<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailerService extends AbstractController
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;


    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    /**
     * @param $token
     * @param $username
     * @param $template
     * @param $to
     */
    public function sendToken($token, $to, $username, $template)
    {
        $message = (new \Swift_Message('Email de confirmation'))
            ->setFrom('hisokath12@gmail.com')
            ->setTo($to)
            ->setBody(
                $this->renderView(
                    'email/' . $template,
                    [
                        'token' => $token,
                        'username' => $username
                    ]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
