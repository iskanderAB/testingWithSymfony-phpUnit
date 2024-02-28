<?php

namespace App\Tests\Unit\Entity;



use App\Entity\Dinosaur;
use App\Type\HealthStatus;
use PHPUnit\Framework\TestCase;

class DinoTest extends TestCase
{
    public function TestWorks(): void
    {
        self::assertEquals(24, 24);
    }


    /**
     * @dataProvider  sizeDescriptionProvider
     */
    public function testDinoHasCorrectSize(int $length, string $exceptedSize )
    {
        $dino = new Dinosaur(name: "iskander", length: $length);

        self::assertSame($exceptedSize, $dino->getSizeDescription());
    }

    function testIsAcceptVisitorByDefault()
    {
        $dino = new Dinosaur(name: "iskanderAB");

        self::assertTrue($dino->isAcceptingVisitors());
    }

    function testIsNotAcceptVisitorIfSick()
    {
        $dino = new Dinosaur("ali ab");

        $dino->setHealth(HealthStatus::SICK);

        self::assertFalse($dino->isAcceptingVisitors());
    }

   function sizeDescriptionProvider(): \Generator
   {
        yield "iskander ab " => [10, 'Large'];
        yield "ali ab " => [5, 'Medium'];
        yield "mohamed ab" => [4, 'Small'];
    }

}