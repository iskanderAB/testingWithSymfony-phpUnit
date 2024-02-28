<?php

namespace App\Tests\Unit\Services;

use App\Service\GithubService;
use App\Type\HealthStatus;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GithubServiceTest extends TestCase
{

    /**
     * @return void
     * @dataProvider dinoNameProvider
     */
    public function testGetCorrectHealthReportForDino(HealthStatus $expectedHealth, string $dinoName): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $httpClient = $this->createMock(HttpClientInterface::class);

        $service = new GithubService($httpClient,$logger);

        self::assertSame($expectedHealth, $service->getHealthReport($dinoName));
    }

    public function dinoNameProvider()
    {
        yield 'Sick Dino' => [
            HealthStatus::SICK,
            'Daisy'
        ];
        yield 'Healthy Dino' => [
            HealthStatus::HEALTHY,
            'Maverick'
        ];
    }
}