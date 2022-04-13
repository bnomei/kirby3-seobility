<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Bnomei\Seobility;
use PHPUnit\Framework\TestCase;

final class SeobilityTest extends TestCase
{
    public function testConstruct()
    {
        $seobility = new Seobility();
        $this->assertInstanceOf(\Bnomei\Seobility::class, $seobility);
    }

    public function testPageView()
    {
        $seobility = new Seobility();

        $data = $seobility->keywordcheck(
            site()->homePage(),
            'atomic design',
            'https://bradfrost.com/blog/post/atomic-web-design/'
        );

        $this->assertEquals(75, $data['score']);
    }
}
