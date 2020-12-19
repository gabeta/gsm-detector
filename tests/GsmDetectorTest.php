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
                'mobile' => ['09', '88']
            ],
            'togocel' => [
                'fix' => ['23'],
                'mobile' => ['04', '05']
            ],
        ]);

        var_dump($gsmDetector->isTogocel('04361076'));

        var_dump($gsmDetector->isTogocelFix('23361076'));

        $this->assertTrue(true);
    }
}
