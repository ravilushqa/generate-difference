<?php

namespace Ravilushqa\Cli;

use function Ravilushqa\Differ\genDiff;

CONST DOC = <<<DOC

Generate diff

Usage:
  gendiff (-h|--help)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  --format <fmt>                Report format [default: pretty]

DOC;

function main()
{
    $args = \Docopt::handle(DOC)->args;

    return genDiff($args['--format'], $args['<firstFile>'], $args['<secondFile>']);
}