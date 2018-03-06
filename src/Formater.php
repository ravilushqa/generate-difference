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
    $prettyDiff = array_reduce($data, function ($acc, $item) {
        switch ($item['type']) {
            case 'not changed':
                $acc .= "    {$item['key']}: {$item['to']}\n";
                break;
            case 'changed':
                $acc .= "  + {$item['key']}: {$item['to']}\n";
                $acc .= "  - {$item['key']}: {$item['from']}\n";
                break;
            case 'removed':
                $acc .= "  - {$item['key']}: {$item['from']}\n";
                break;
            case 'added':
                $acc .= "  + {$item['key']}: {$item['to']}\n";
                break;
        }

        return $acc;
    }, '');

    return <<<PRETTY
{
$prettyDiff}
PRETTY;
}
