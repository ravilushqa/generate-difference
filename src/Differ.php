<?php

namespace Ravilushqa\Differ;

use function Funct\Collection\union;
use function Ravilushqa\Helpers\getFileContent;
use function Ravilushqa\Helpers\getFileExtension;
use function Ravilushqa\Parser\format;
use function Ravilushqa\Parser\parse;

const SUPPORTED_FILES = [
    'json',
    'yaml'
];
const SUPPORTED_REPORTS = [
    'pretty',
    'plain',
    'json'
];

/**
 * @param $format
 * @param $firstFile
 * @param $secondFile
 * @return mixed
 * @throws \Exception
 */
function genDiff($firstFile, $secondFile, $format = 'pretty')
{
    validateInputData($format, $firstFile, $secondFile);

    $firstFileData = parse(getFileContent($firstFile), getFileExtension($firstFile));
    $secondFileData = parse(getFileContent($secondFile), getFileExtension($secondFile));

    $ast = generateAst($firstFileData, $secondFileData);

    return format($ast, $format);
}

/**
 * @param $format
 * @param $firstFile
 * @param $secondFile
 * @throws \Exception
 */
function validateInputData($format, $firstFile, $secondFile)
{
    if (!in_array($format, SUPPORTED_REPORTS)) {
        throw new \Exception("Not supported report format: $format\n");
    }
    foreach ([$firstFile, $secondFile] as $file) {
        if (!in_array(getFileExtension($file), SUPPORTED_FILES)) {
            throw new \Exception("Not supported file format: " . getFileExtension($file).PHP_EOL);
        }
    }
}

/**
 * @param array $firstFileData
 * @param array $secondFileData
 * @return mixed
 */
function generateAst(array $firstFileData, array $secondFileData)
{
    $union = union(array_keys($firstFileData), array_keys($secondFileData));

    return array_reduce($union, function ($acc, $item) use ($firstFileData, $secondFileData) {
        $acc[] = collectDiffData($item, $firstFileData, $secondFileData);
        return $acc;
    }, []);
}

/**
 * @param $item
 * @param $beforeArray
 * @param $afterArray
 * @return array
 */
function collectDiffData($item, $beforeArray, $afterArray)
{
    if (array_key_exists($item, $beforeArray) && array_key_exists($item, $afterArray)) {
        if (is_array($beforeArray[$item]) && is_array($afterArray[$item])) {
            return [
                'key' => $item,
                'type' => 'node',
                'children' => generateAst($beforeArray[$item], $afterArray[$item])
            ];
        } else {
            if ($beforeArray[$item] === $afterArray[$item]) {
                return [
                'key' => $item,
                'type' => 'not changed',
                'from' => $beforeArray[$item],
                'to' => $afterArray[$item],
                ];
            } else {
                return [
                'key' => $item,
                'type' => 'changed',
                'from' => $beforeArray[$item],
                'to' => $afterArray[$item],
                ];
            }
        }
    }

    if (array_key_exists($item, $beforeArray) && !array_key_exists($item, $afterArray)) {
        return [
            'key'  => $item,
            'type' => 'removed',
            'from' => $beforeArray[$item],
            'to'   => null,
        ];
    }

    if (!array_key_exists($item, $beforeArray) && array_key_exists($item, $afterArray)) {
        return [
            'key'  => $item,
            'type' => 'added',
            'from' => null,
            'to'   => $afterArray[$item],
        ];
    }
}
