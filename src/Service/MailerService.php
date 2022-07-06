<?php

namespace App\Service;

use App\Entity\Booking;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailerService
{
    private array $emailConfig;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->emailConfig = $parameterBag->get('emailConfig');
    }

    /**
     * @throws Exception
     */
    public function send(Booking $booking): void
    {
        $smtp = $this->emailConfig['smtp'];
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host = $smtp['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $this->emailConfig['username'];
        $mail->Password = $this->emailConfig['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = $smtp['port'];

        $mail->setFrom($this->emailConfig['username'], $this->emailConfig['name']);
        $mail->addAddress($booking->getEmail(), $booking->getFullName());

        $mail->isHTML(true);
        $mail->Subject = 'Booking #'.$booking->getId();
        $mail->Body = '<div style="font-size: 23px; ">
        Total:'.$booking->getTotal().
            '<br>'.'Booking date: '.$booking->getCreatedAt()->format('Y-m-d H:i:s').
            '<br>'.'Booking user: '.$booking->getFullName().
            '<br>'.'Booking user email: '.$booking->getEmail().
            '<br>'.'Booking user phone: '.$booking->getPhone().
            '</div>';

        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
    }
}
