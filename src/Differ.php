<?php

namespace Ravilushqa\Differ;

use function Funct\Collection\union;
use function Ravilushqa\Helpers\getFileExtension;
use function Ravilushqa\Parser\format;
use function Ravilushqa\Parser\parse;

CONST SUPPORTED_FILES = [
    'json'
];
CONST SUPPORTED_REPORTS = [
    'pretty'
];

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
 */
function validateInputData($format, $firstFile, $secondFile)
{
    try {
        if (!in_array($format, SUPPORTED_REPORTS)) {
            throw new \Exception("Not supported report format: $format");
        }
        foreach ([$firstFile, $secondFile] as $file) {
            if (!in_array(getFileExtension($file), SUPPORTED_FILES)){
                throw new \Exception("Not supported file format: " . getFileExtension($file));
            }
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
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
        if (array_key_exists($item, $notChanged)) {
            $acc[] = [
                'key'  => $item,
                'type' => 'not changed',
                'from' => $notChanged[$item],
                'to' => $notChanged[$item],
            ];
        } elseif (array_key_exists($item, $added) && array_key_exists($item, $removed)) {
            $acc[] = [
                'key'  => $item,
                'type' => 'changed',
                'from' => $removed[$item],
                'to' => $added[$item],
            ];

        } elseif (array_key_exists($item, $added) && !array_key_exists($item, $removed)) {
            $acc[] = [
                'key'  => $item,
                'type' => 'added',
                'from' => "",
                'to' => $added[$item],
            ];
        } else {
            $acc[] = [
                'key'  => $item,
                'type' => 'removed',
                'from' => "",
                'to' => $removed[$item],
            ];
        }
        return $acc;
    }, []);

}