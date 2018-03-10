<?php

namespace Ravilushqa\Parser;

/**
 * @param array $ast
 * @param $format
 * @return mixed
 */
function format(array $ast, $format)
{
    return getArrayByFormat()[$format]($ast);
}

/**
 * @return array
 */
function getArrayByFormat()
{
    return [
        'pretty' => function (array $ast) {
            return fromDiffArrayToPretty($ast);
        }
    ];
}

/**
 * @param array $ast
 * @param int $level
 * @return string
 */
function fromDiffArrayToPretty(array $ast, $level = 0)
{
    $prettyStringsArray = array_reduce($ast, function ($acc, $item) use ($level) {
        switch ($item['type']) {
            case 'node':
                break;
            case 'not changed':
                if (is_array($item['to'])) {
                } else {
                    $acc[] = getPrettyRow($item['key'], $item['to'], ' ', $level);
                }
                break;
            case 'changed':
                if (is_array($item['to'])) {
                } else {
                    $acc[] = getPrettyRow($item['key'], $item['to'], '+', $level);
                    $acc[] = getPrettyRow($item['key'], $item['from'], '-', $level);
                }
                break;
            case 'removed':
                if (is_array($item['from'])) {
                } else {
                    $acc[] = getPrettyRow($item['key'], $item['from'], '-', $level);
                }
                break;
            case 'added':
                if (is_array($item['to'])) {
                } else {
                    $acc[] = getPrettyRow($item['key'], $item['to'], '+', $level);
                }
                break;
        }

        return $acc;
    }, []);

    $prettyResult = "{\n" . implode(PHP_EOL, $prettyStringsArray) . "\n}";

    return <<<PRETTY
$prettyResult
PRETTY;
}

function prepareValue($value)
{
    return is_bool($value) ? var_export($value, true) : $value;
}

function getPrettyIndent(int $level)
{
    return str_repeat(' ', $level * 4 + 2);
}

function getPrettyRow(string $key, $value, string $prefix, int $level)
{
    return implode(
        [
            getPrettyIndent($level),
            $prefix,
            " {$key}: ",
            prepareValue($value)
        ]
    );
}
