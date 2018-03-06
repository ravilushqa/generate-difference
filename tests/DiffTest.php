<?php

namespace Ravilushqa\Differ\Tests;

use \PHPUnit\Framework\TestCase;
use function Ravilushqa\Differ\genDiff;

class DiffTest extends TestCase
{
    protected $filesDir = __DIR__ . '/utilities';

    /** @test */
    public function user_should_get_exception_when_he_uses_wrong_format()
    {
        $format = 'wrong';
        $this->expectExceptionMessage("Not supported report format: $format\n");

        $firstFilePath = "$this->filesDir/before.json";
        $secondFilePath = "$this->filesDir/after.json";

        genDiff($format, $firstFilePath, $secondFilePath);
    }

    /** @test */
    public function user_should_get_exception_when_he_uses_wrong_file_path()
    {
        $wrongPath = "$this->filesDir/wrong-path/after.json";
        $this->expectExceptionMessage("file $wrongPath not found");

        $firstFilePath = "$this->filesDir/before.json";

        genDiff('pretty', $firstFilePath, $wrongPath);
    }

    /** @test */
    public function user_may_diff_flat_jsons_and_get_it_in_pretty_format()
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

        $result = genDiff('pretty', $firstFilePath, $secondFilePath);

        $this->assertEquals($expected, $result);
    }

}