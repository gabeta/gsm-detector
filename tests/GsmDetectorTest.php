<?php


namespace Gabeta\GsmDetector\Test;


use Gabeta\GsmDetector\GsmDetector;
use PHPUnit\Framework\TestCase;

class GsmDetectorTest extends TestCase
{
    public function test_is_gsm()
    {
        $gsmDetector = new GsmDetector([
            'orange' => [
                'fix' => ['22', '35'],
                'mobile' => ['88', '87']
            ],
            'togocel' => [
                'fix' => ['23', '24'],
                'mobile' => ['56', '01']
            ],
        ]);

        $this->assertTrue($gsmDetector->isGsm('togocel', '56000000'));
        $this->assertTrue($gsmDetector->isGsm('togocel', '23000000'));
        $this->assertTrue($gsmDetector->isGsm('orange', '88000000'));
        $this->assertTrue($gsmDetector->isGsm('orange', '35000000'));
        $this->assertFalse($gsmDetector->isGsm('togocel', '26000000'));
        $this->assertFalse($gsmDetector->isGsm('orange', '29000000'));
    }

    public function test_is_gsm_with_type()
    {
        $gsmDetector = new GsmDetector([
            'orange' => [
                'fix' => ['22', '35'],
                'mobile' => ['88', '87']
            ],
            'togocel' => [
                'fix' => ['23', '24'],
                'mobile' => ['56', '01']
            ],
        ]);

        $this->assertTrue($gsmDetector->isGsmWithType('togocel', 'mobile', '56000000'));
        $this->assertFalse($gsmDetector->isGsmWithType('togocel','mobile', '23000000'));
        $this->assertTrue($gsmDetector->isGsmWithType('orange','mobile', '88000000'));
        $this->assertFalse($gsmDetector->isGsmWithType('orange', 'mobile', '35000000'));
        $this->assertFalse($gsmDetector->isGsmWithType('togocel', 'fix', '01000000'));
        $this->assertTrue($gsmDetector->isGsmWithType('orange','fix', '35000000'));
    }

    public function test_is_gsm_name()
    {
        $gsmDetector = new GsmDetector([
            'orange' => [
                'fix' => ['22', '35'],
                'mobile' => ['09', '88']
            ],
            'togocel' => [
                'fix' => ['23', '24'],
                'mobile' => ['04', '05']
            ],
        ]);

        $this->assertTrue($gsmDetector->isTogocel('04000000'));
        $this->assertTrue($gsmDetector->isTogocel('24000000'));
        $this->assertFalse($gsmDetector->isTogocel('35000000'));
        $this->assertFalse($gsmDetector->isTogocel('08000000'));
        $this->assertTrue($gsmDetector->isTogocel('23000000'));
        $this->assertTrue($gsmDetector->isOrange('88000000'));
        $this->assertTrue($gsmDetector->isOrange('09000000'));
        $this->assertTrue($gsmDetector->isOrange('22000000'));
    }

    public function test_is_gsm_name_with_type()
    {
        $gsmDetector = new GsmDetector([
            'orange' => [
                'fix' => ['22', '35'],
                'mobile' => ['09', '88']
            ],
            'togocel' => [
                'fix' => ['23', '24'],
                'mobile' => ['04', '05']
            ],
        ]);

        $this->assertTrue($gsmDetector->isTogocelFix('23000000'));
        $this->assertTrue($gsmDetector->isTogocelFix('24000000'));
        $this->assertFalse($gsmDetector->isTogocelFix('04000000'));
        $this->assertTrue($gsmDetector->isTogocelMobile('04000000'));
        $this->assertTrue($gsmDetector->isOrangeFix('22000000'));
        $this->assertTrue($gsmDetector->isOrangeFix('35000000'));
        $this->assertFalse($gsmDetector->isOrangeMobile('35000000'));
    }

    public function test_get_gsm_name()
    {
        $gsmDetector = new GsmDetector([
            'orange' => [
                'fix' => ['22', '35'],
                'mobile' => ['09', '88']
            ],
            'togocel' => [
                'fix' => ['23', '24'],
                'mobile' => ['04', '05']
            ],
        ]);

        $this->assertEquals($gsmDetector->getGsmName('22000000'), 'orange');
        $this->assertNull($gsmDetector->getGsmName('20000000'));
        $this->assertEquals($gsmDetector->getGsmName('05000000'), 'togocel');
    }

    public function test_is_type()
    {
        $gsmDetector = new GsmDetector([
            'orange' => [
                'fix' => ['22', '35'],
                'mobile' => ['09', '88']
            ],
            'togocel' => [
                'fix' => ['23', '24'],
                'mobile' => ['04', '05']
            ],
        ]);

        $this->assertTrue($gsmDetector->isMobile('04000000'));
        $this->assertFalse($gsmDetector->isMobile('23000000'));
        $this->assertTrue($gsmDetector->isFix('23000000'));
        $this->assertFalse($gsmDetector->isFix('8800000'));
    }
}
