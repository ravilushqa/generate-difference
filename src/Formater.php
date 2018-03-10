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
    $prettyStringsArray = array_reduce(prepareDataToString($data), function ($acc, $item) {
        switch ($item['type']) {
            case 'not changed':
                $acc[] = "    {$item['key']}: {$item['to']}";
                break;
            case 'changed':
                $acc[] = "  + {$item['key']}: {$item['to']}";
                $acc[] = "  - {$item['key']}: {$item['from']}";
                break;
            case 'removed':
                $acc[] = "  - {$item['key']}: {$item['from']}";
                break;
            case 'added':
                $acc[] = "  + {$item['key']}: {$item['to']}";
                break;
        }

        return $acc;
    }, []);

    $prettyResult = "{\n" . implode(PHP_EOL, $prettyStringsArray) . "\n}";

    return <<<PRETTY
$prettyResult
PRETTY;
}

function prepareDataToString($data)
{
    return array_map(function ($item) {
        $item['from'] = is_bool($item['from']) ? var_export($item['from'], true) : $item['from'];
        $item['to'] = is_bool($item['to']) ? var_export($item['to'], true) : $item['to'];

        return $item;
    }, $data);
}
