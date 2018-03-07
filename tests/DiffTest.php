<?php

namespace Ravilushqa\Differ\Tests;

use \PHPUnit\Framework\TestCase;
use function Ravilushqa\Differ\genDiff;

class DiffTest extends TestCase
{
    protected $filesDir = __DIR__ . '/fixtures';

    public function testWrongFormat()
    {
        $format = 'wrong';
        $this->expectExceptionMessage("Not supported report format: $format\n");

        $firstFilePath = "$this->filesDir/before.json";
        $secondFilePath = "$this->filesDir/after.json";

        genDiff($firstFilePath, $secondFilePath, $format);
    }

    public function testWrongFilePath()
    {
        $wrongPath = "$this->filesDir/wrong-path/after.json";
        $this->expectExceptionMessage("file $wrongPath not found");

        $firstFilePath = "$this->filesDir/before.json";

        genDiff($firstFilePath, $wrongPath);
    }

    public function testFlatJsonInPrettyFormat()
    {
        $expected = include_once __DIR__ . '/fixtures/prettyExpected.php';

        $firstFilePath = "$this->filesDir/before.json";
        $secondFilePath = "$this->filesDir/after.json";

        $result = genDiff($firstFilePath, $secondFilePath);

        $this->assertEquals($expected, $result);
    }

}