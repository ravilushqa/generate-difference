<?php

namespace Ravilushqa\Parser;

use function Ravilushqa\Helpers\getFileExtension;
use Symfony\Component\Yaml\Yaml;

/**
 * @param $filePath
 * @return mixed
 * @throws \Exception
 */
function parse($filePath)
{
    return getArrayByExt()[getFileExtension($filePath)](file_get_contents($filePath));
}

/**
 * @return array
 */
function getArrayByExt()
{
    return [
        'json' => function ($file) {
            return json_decode($file, true);
        },
        'yaml' => function ($file) {
            $parsed = Yaml::parse($file, Yaml::PARSE_OBJECT_FOR_MAP);

            return (array) $parsed;
        }
    ];
}
