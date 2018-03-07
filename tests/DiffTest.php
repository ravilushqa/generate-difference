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
        $expected = <<<PRETTY
{
    host: hexlet.io
  + timeout: 20
  - timeout: 50
  - proxy: 123.234.53.22
  + verbose: true
}
PRETTY;

        $firstFilePath = "$this->filesDir/before.json";
        $secondFilePath = "$this->filesDir/after.json";

        $result = genDiff($firstFilePath, $secondFilePath);

        $this->assertEquals($expected, $result);
    }

}