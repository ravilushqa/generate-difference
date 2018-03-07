<?php

namespace Ravilushqa\Parser;

/**
 * @param array $data
 * @param $format
 * @return mixed
 */
function format(array $data, $format)
{
    return getArrayByFormat()[$format]($data);
}

/**
 * @return array
 */
function getArrayByFormat()
{
    return [
        'pretty' => function (array $data) {
            return fromDiffArrayToPretty($data);
        }
    ];
}

/**
 * @param $data
 * @return string
 */
function fromDiffArrayToPretty($data)
{
    $prettyStringsArray = array_reduce($data, function ($acc, $item) {
        switch ($item['type']) {
            case 'not changed':
                array_push($acc, "    {$item['key']}: {$item['to']}");
                break;
            case 'changed':
                array_push($acc,"  + {$item['key']}: {$item['to']}");
                array_push($acc,"  - {$item['key']}: {$item['from']}");
                break;
            case 'removed':
                array_push($acc,"  - {$item['key']}: {$item['from']}");
                break;
            case 'added':
                array_push($acc,"  + {$item['key']}: {$item['to']}");
                break;
        }

        return $acc;
    }, []);

    $prettyResult = "{\n" . implode(PHP_EOL, $prettyStringsArray) . "\n}";

    return <<<PRETTY
$prettyResult
PRETTY;
}
