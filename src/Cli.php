<?php

namespace Ravilushqa\Cli;

use function Ravilushqa\Differ\genDiff;

const DOC = <<<DOC

Generate diff

Usage:
  gendiff (-h|--help)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  --format <fmt>                Report format [default: pretty]

DOC;

/**
 * @throws \Exception
 */
function main()
{
    $args = \Docopt::handle(DOC)->args;

    echo genDiff($args['<firstFile>'], $args['<secondFile>'], $args['--format']) . PHP_EOL;

    return;
}
