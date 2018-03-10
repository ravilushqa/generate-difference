<?php

namespace Ravilushqa\Parser;

const PRETTY_INDENT_COUNTER = 4;
const PRETTY_INDENT_DEFAULT = 2;
const PRETTY_PREFIX_NOT_CHANGED = '  ';
const PRETTY_PREFIX_REMOVED= '- ';
const PRETTY_PREFIX_ADDED= '+ ';
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
            return fromAstToPretty($ast);
        }
    ];
}

/**
 * @param array $ast
 * @param int $level
 * @return string
 */
function fromAstToPretty(array $ast, $level = 0)
{
    $iter = function (array $branch, int $level) use (&$iter) {
        return array_reduce($branch, function ($acc, $item) use ($iter, $level) {
            switch ($item['type']) {
                case 'not changed':
                    if (isNode($item)) {
                        $acc[] = implode(
                            [
                                getPrettyIndent($level),
                                PRETTY_PREFIX_NOT_CHANGED,
                                "{$item['key']}: {",
                                $iter($item['children'], $level + 1),
                                getPrettyIndent($level),
                                "  }"
                            ]
                        );
                    } else {
                        $acc[] = getPrettyRow($item['key'], $item['to'], PRETTY_PREFIX_NOT_CHANGED, $level);
                    }
                    break;
                case 'changed':
                    if (isNode($item)) {
                        $acc[] = implode(
                            [
                                getPrettyIndent($level),
                                PRETTY_PREFIX_NOT_CHANGED,
                                "{$item['key']}: {",
                                $iter($item['children'], $level + 1),
                                getPrettyIndent($level),
                                "  }"
                            ]
                        );
                    } else {
                        $acc[] = getPrettyRow($item['key'], $item['to'], PRETTY_PREFIX_ADDED, $level);
                        $acc[] = getPrettyRow($item['key'], $item['from'], PRETTY_PREFIX_REMOVED, $level);
                    }
                    break;
                case 'removed':
                    if (isNode($item)) {
                    } else {
                        $acc[] = getPrettyRow($item['key'], $item['from'], PRETTY_PREFIX_REMOVED, $level);
                    }
                    break;
                case 'added':
                    if (isNode($item)) {
                    } else {
                        $acc[] = getPrettyRow($item['key'], $item['to'], PRETTY_PREFIX_ADDED, $level);
                    }
                    break;
            }

            return $acc;
        }, []);
    };

    $prettyStringsArray = $iter($ast, $level);

    $prettyResult = "{\n" . implode(PHP_EOL, $prettyStringsArray) . "\n}";

    return $prettyResult;
}

function prepareValue($value)
{
    return is_bool($value) ? var_export($value, true) : $value;
}

function getPrettyIndent(int $level)
{
    return str_repeat(' ', $level * PRETTY_INDENT_COUNTER + PRETTY_INDENT_DEFAULT);
}

function getPrettyRow(string $key, $value, string $prefix, int $level)
{
    return implode(
        [
            getPrettyIndent($level),
            $prefix,
            "{$key}: ",
            prepareValue($value)
        ]
    );
}

function isNode($item)
{
    return $item['treePart'] === 'node' && array_key_exists('children', $item);
}
