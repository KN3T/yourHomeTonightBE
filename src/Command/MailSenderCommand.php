<?php

namespace App\Command;

use App\Repository\BookingRepository;
use App\Service\MailerService;
use Aws\Sqs\SqsClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

#[AsCommand(name: 'app:send-mail')]
class MailSenderCommand extends Command
{
    private BookingRepository $bookingRepository;
    private MailerService $mailerService;
    private SqsClient $sqsClient;
    private ContainerBagInterface $params;

    public function __construct(
        BookingRepository $bookingRepository,
        MailerService $mailerService,
        SqsClient $sqsClient,
        ContainerBagInterface $params,
        string $name = null
    ) {
        parent::__construct($name);
        $this->bookingRepository = $bookingRepository;
        $this->mailerService = $mailerService;
        $this->sqsClient = $sqsClient;
        $this->params = $params;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bookingIds = $this->getBookingFromQueue();
        if (empty($bookingIds)) {
            return Command::FAILURE;
        }
        foreach ($bookingIds as $bookingId) {
            $booking = $this->bookingRepository->find($bookingId);
            $this->mailerService->send($booking);
            $output->writeln('Mail sent to '.$booking->getEmail());
        }

        return Command::SUCCESS;
    }

    private function getBookingFromQueue(): array
    {
        $sqsUrl = $this->params->get('sqsUrl');
        $bookingIds = [];

        while (true) {
            $result = $this->sqsClient->receiveMessage([
                'AttributeNames' => ['SentTimestamp'],
                'MaxNumberOfMessages' => 1,
                'MessageAttributeNames' => ['All'],
                'QueueUrl' => $sqsUrl,
                'WaitTimeSeconds' => 0,
            ]);

            if (null === $result->get('Messages')) {
                break;
            }

            $bookingId = $result->get('Messages')[0]['Body'];
            $this->sqsClient->deleteMessage([
                'QueueUrl' => $sqsUrl,
                'ReceiptHandle' => $result->get('Messages')[0]['ReceiptHandle'],
            ]);
            $bookingIds[] = $bookingId;
        }

        return $bookingIds;
    }
}
