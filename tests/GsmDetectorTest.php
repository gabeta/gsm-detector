<?php


namespace Gabeta\GsmDetector\Test;


use Gabeta\GsmDetector\GsmDetector;
use PHPUnit\Framework\TestCase;

class GsmDetectorTest extends TestCase
{
    public function test_default()
    {
        $gsmDetector = new GsmDetector([
            'moov' => [
                'fix' => ['20'],
                'mobile' => ['03']
            ],
            'orange' => [
                'fix' => ['22'],
                'mobile' => ['09']
            ],
        ]);

        $this->assertTrue(true);
    }
}
