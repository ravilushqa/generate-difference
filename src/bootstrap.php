<?php

$autoloadPath1 = __DIR__ . '/../../../autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';
$docoptPath1 = __DIR__.'/../../../docopt/docopt/src/docopt.php';
$docoptPath2 = __DIR__.'/../vendor/docopt/docopt/src/docopt.php';
if (file_exists($autoloadPath1)) {
    include_once $autoloadPath1;
    include_once $docoptPath1;
} else {
    include_once $autoloadPath2;
    include_once $docoptPath2;
}
