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
        $this->expectExceptionMessage("Not supported report format: {$format}\n");

        $firstFilePath = "{$this->filesDir}/flatBefore.json";
        $secondFilePath = "{$this->filesDir}/flatAfter.json";

        genDiff($firstFilePath, $secondFilePath, $format);
    }

    public function testWrongFilePath()
    {
        $wrongPath = "{$this->filesDir}/wrong-path/flatAfter.json";
        $this->expectExceptionMessage("file {$wrongPath} not found");

        $firstFilePath = "{$this->filesDir}/flatBefore.json";

        genDiff($firstFilePath, $wrongPath);
    }

    public function testFlatJsonInPrettyFormat()
    {
        $expected = file_get_contents(__DIR__ . '/fixtures/prettyExpected.txt');

        $firstFilePath = "{$this->filesDir}/flatBefore.json";
        $secondFilePath = "{$this->filesDir}/flatAfter.json";

        $result = genDiff($firstFilePath, $secondFilePath);

        $this->assertEquals($expected, $result);
    }

    public function testFlatYamlInPrettyFormat()
    {
        $expected = file_get_contents(__DIR__ . '/fixtures/prettyExpected.txt');

        $firstFilePath = "{$this->filesDir}/flatBefore.yaml";
        $secondFilePath = "{$this->filesDir}/flatAfter.yaml";

        $result = genDiff($firstFilePath, $secondFilePath);


        $this->assertEquals($expected, $result);
    }

    public function testNestedJsonInPrettyFormat()
    {
        $expected = file_get_contents(__DIR__ . '/fixtures/nestedPrettyExpected.txt');

        $firstFilePath = "{$this->filesDir}/nestedBefore.json";
        $secondFilePath = "{$this->filesDir}/nestedAfter.json";

        $result = genDiff($firstFilePath, $secondFilePath);


        $this->assertEquals($expected, $result);
    }

    public function testNestedJsonInPlainFormat()
    {
        $expected = file_get_contents(__DIR__ . '/fixtures/nestedPlainExpected.txt');

        $firstFilePath = "{$this->filesDir}/nestedBefore.json";
        $secondFilePath = "{$this->filesDir}/nestedAfter.json";

        $result = genDiff($firstFilePath, $secondFilePath, 'plain');


        $this->assertEquals($expected, $result);
    }

    public function testNestedJsonInJsonFormat()
    {
        $expected = file_get_contents(__DIR__ . '/fixtures/nestedJsonExpected.json');

        $firstFilePath = "{$this->filesDir}/nestedBefore.json";
        $secondFilePath = "{$this->filesDir}/nestedAfter.json";

        $result = genDiff($firstFilePath, $secondFilePath, 'json');


        $this->assertEquals($expected, $result);
    }

}