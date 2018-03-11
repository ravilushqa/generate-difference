<?php

namespace Ravilushqa\Parser;

use Funct\Collection;

const PRETTY_INDENT_COUNTER = 4;
const PRETTY_INDENT_LENGTH_WITHOUT_PREFIX = 2;
const PRETTY_PREFIX_NOT_CHANGED = ' ';
const PRETTY_PREFIX_REMOVED= '-';
const PRETTY_PREFIX_ADDED= '+';
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
        'plain' => function (array $ast) {
            return fromAstToPlain($ast);
        },
        'pretty' => function (array $ast) {
            return fromAstToPretty($ast);
        }
    ];
}

function fromAstToPlain(array $ast)
{
    $iter = function ($ast, $parents) use (&$iter) {
        return array_reduce($ast, function ($acc, $item) use ($iter, $parents) {
            $parents[] = $item['key'];
            $pathToNode = implode('.', $parents);
            switch ($item['type']) {
                case 'node':
                    $acc = array_merge($acc, $iter($item['children'], $parents));
                    break;
                case 'added':
                    if (is_array($item['to'])) {
                        $acc[] = "Property '{$pathToNode}' was added with value: 'complex value'";
                    } else {
                        $acc[] = "Property '{$pathToNode}' was added with value: '{$item['to']}'";
                    }
                    break;
                case 'removed':
                    $acc[] = "Property '{$pathToNode}' was removed";
                    break;
                case 'changed':
                    if (is_array($item['to'])) {
                        $acc[] = "Property '{$pathToNode}' was changed. From '{$item['from']}' to 'complex value'";
                    } else {
                        $acc[] = "Property '{$pathToNode}' was changed. From '{$item['from']}' to '{$item['to']}'";
                    }

                    break;
            }
            return $acc;
        }, []);
    };

    return implode(PHP_EOL, $iter($ast, []));
}

/**
 * @param array $ast
 * @return string
 */
function fromAstToPretty(array $ast)
{
    $iter = function (array $branch, int $level) use (&$iter) {
        return array_map(function ($item) use ($level, $iter) {
            switch ($item['type']) {
                case 'node':
                    return [
                        getPrettyIndent($level) . "  {$item['key']}: {",
                        $iter($item['children'], $level + 1),
                        getPrettyIndent($level) . "  }"
                    ];
                case 'not changed':
                    if (is_array($item['to'])) {
                        return getPrettyBranch($item['key'], $item['to'], $level);
                    } else {
                        return getPrettyRow($item['key'], $item['to'], $level);
                    }
                    break;
                case 'added':
                    if (is_array($item['to'])) {
                        return getPrettyBranch($item['key'], $item['to'], $level, PRETTY_PREFIX_ADDED);
                    } else {
                        return getPrettyRow($item['key'], $item['to'], $level, PRETTY_PREFIX_ADDED);
                    }
                    break;
                case 'removed':
                    if (is_array($item['from'])) {
                        return getPrettyBranch($item['key'], $item['from'], $level, PRETTY_PREFIX_REMOVED);
                    } else {
                        return getPrettyRow($item['key'], $item['from'], $level, PRETTY_PREFIX_REMOVED);
                    }
                    break;
                case 'changed':
                    if (is_array($item['to'])) {
                        $result[] = getPrettyBranch($item['key'], $item['to'], $level, PRETTY_PREFIX_ADDED);
                    } else {
                        $result[] = getPrettyRow($item['key'], $item['to'], $level, PRETTY_PREFIX_ADDED);
                    }
                    if (is_array($item['from'])) {
                        $result[] = getPrettyBranch($item['key'], $item['from'], $level, PRETTY_PREFIX_REMOVED);
                    } else {
                        $result[] = getPrettyRow($item['key'], $item['from'], $level, PRETTY_PREFIX_REMOVED);
                    }
                    return $result;
                default:
                    return '';
            }
        }, $branch);
    };
    return implode(
        PHP_EOL,
        array_merge(
            ['{'],
            Collection\flattenAll($iter($ast, 0)),
            ['}']
        )
    );
}

function prepareValue($value)
{
    return is_bool($value) ? var_export($value, true) : $value;
}

function getPrettyIndent(int $level)
{
    return str_repeat(' ', $level * PRETTY_INDENT_COUNTER + PRETTY_INDENT_LENGTH_WITHOUT_PREFIX);
}

function getPrettyRow(string $key, $value, int $level, string $prefix = PRETTY_PREFIX_NOT_CHANGED)
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

function getPrettyBranch(string $key, array $value, int $level, string $prefix = PRETTY_PREFIX_NOT_CHANGED)
{
    $map = array_map(function ($key) use ($value, $level) {
        return getPrettyRow($key, $value[$key], $level + 1);
    }, array_keys($value));

    return [
        getPrettyIndent($level) . "{$prefix} {$key}: {",
        $map,
        getPrettyIndent($level) . "  }"
    ];
}
