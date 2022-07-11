<?php

namespace App\Service;

use App\Entity\Booking;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailerService
{
    private array $emailConfig;
    private $projectDir;

    public function __construct(ParameterBagInterface $parameterBag, string $projectDir)
    {
        $this->emailConfig = $parameterBag->get('emailConfig');
        $this->projectDir = $projectDir;
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
        $mail->Subject = 'Booking #' . $booking->getId();
        $mail->Body = $this->getEmailTemplate($booking);

        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->send();
    }

    private function getEmailTemplate(Booking $booking): string
    {
        $now = new \DateTime();
        $now = $now->format('Y-m-d H:i:s');
        $mailBody = file_get_contents($this->projectDir . '/templates/emailTemplate.html');
        $mailBody = str_replace('%now%', $now, $mailBody);
        $mailBody = str_replace('%hotelName%', $booking->getRoom()->getHotel()->getName(), $mailBody);
        $mailBody = str_replace('%name%', $booking->getFullName(), $mailBody);
        $mailBody = str_replace('%hotelDescription%', $booking->getRoom()->getHotel()->getDescription(), $mailBody);
        $mailBody = str_replace('%roomType%', $booking->getRoom()->getType(), $mailBody);
        $mailBody = str_replace('%checkin%', $booking->getCheckIn()->format('Y-m-d'), $mailBody);
        $mailBody = str_replace('%checkout%', $booking->getCheckOut()->format('Y-m-d'), $mailBody);
        $mailBody = str_replace('%total%', $booking->getTotal(), $mailBody);
        $mailBody = str_replace(
            '%imageHotel%',
            $booking->getRoom()->getHotel()->getHotelImages()->toArray()[0]->getImage()->getPath(),
            $mailBody
        );

        return $mailBody;
    }
}
