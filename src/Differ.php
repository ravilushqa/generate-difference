<?php

namespace Ravilushqa\Differ;

use function Funct\Collection\union;
use function Ravilushqa\Helpers\getFileExtension;
use function Ravilushqa\Parser\format;
use function Ravilushqa\Parser\parse;

const SUPPORTED_FILES = [
    'json'
];
const SUPPORTED_REPORTS = [
    'pretty'
];

/**
 * @param $format
 * @param $firstFile
 * @param $secondFile
 * @return mixed
 * @throws \Exception
 */
function genDiff($format, $firstFile, $secondFile)
{
    validateInputData($format, $firstFile, $secondFile);
    $diffArray = arraysDiff(parse($firstFile), parse($secondFile));

    return format($diffArray, $format);
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
 * @param array $firstFile
 * @param array $secondFile
 * @return mixed
 */
function arraysDiff(array $firstFile, array $secondFile)
{
    $union = union(array_keys($firstFile), array_keys($secondFile));
    $added = collect($secondFile)->diffAssoc(collect($firstFile))->toArray();
    $removed = collect($firstFile)->diffAssoc(collect($secondFile))->toArray();
    $notChanged = collect($firstFile)->intersect(collect($secondFile))->toArray();

    return array_reduce($union, function ($acc, $item) use ($added, $removed, $notChanged) {
        $acc[] = collectDiffData($item, $added, $removed, $notChanged);
        return $acc;
    }, []);
}

/**
 * @param $item
 * @param $added
 * @param $removed
 * @param $notChanged
 * @return array
 */
function collectDiffData($item, $added, $removed, $notChanged)
{
    if (array_key_exists($item, $notChanged)) {
        return [
            'key'  => $item,
            'type' => 'not changed',
            'from' => $notChanged[$item],
            'to'   => $notChanged[$item],
        ];
    } elseif (array_key_exists($item, $added) && array_key_exists($item, $removed)) {
        return [
            'key'  => $item,
            'type' => 'changed',
            'from' => $removed[$item],
            'to'   => $added[$item],
        ];
    } elseif (array_key_exists($item, $added) && !array_key_exists($item, $removed)) {
        return [
            'key'  => $item,
            'type' => 'added',
            'from' => "",
            'to'   => var_export($added[$item], true) ,
        ];
    } else {
        return [
            'key'  => $item,
            'type' => 'removed',
            'from' => $removed[$item],
            'to'   => "",
        ];
    }
}
