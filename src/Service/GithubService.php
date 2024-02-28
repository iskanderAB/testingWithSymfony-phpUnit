<?php

namespace App\Service;

use App\Type\HealthStatus;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GithubService
{

    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
    ){}

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getHealthReport(string $dinoName)
    {
        $health = HealthStatus::HEALTHY;

        $response = $this->httpClient->request(
            method: 'GET',
            url: 'https://api.github.com/repos/SymfonyCasts/dino-park/issues'
        );

        $this->logger->info(
            'request Dino issues',
            [
                'dino' => $dinoName,
                'responseStatus' => $response->getStatusCode()
            ]
        );
        foreach ($response->toArray() as $issue) {
            if (str_contains($issue['title'], $dinoName)) {
                $health = $this->getDinoStatusFromLabels($issue['labels']);
            }
        }
        return $health;
    }


    private function getDinoStatusFromLabels(array $labels): HealthStatus
    {
        $status = null;
        foreach ($labels as $label) {
            $label = $label['name'];

            if (!str_starts_with($label, 'Status:')) {
                continue;
            }

            $status = trim(substr($label, strlen('Status:')));
        }
        return HealthStatus::tryFrom($status);
    }

}