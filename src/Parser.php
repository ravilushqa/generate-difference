<?php

namespace Ravilushqa\Parser;

use function Ravilushqa\Helpers\getFileExtension;
use Symfony\Component\Yaml\Yaml;

/**
 * @param $content
 * @param $extension
 * @return mixed
 * @throws \Exception
 */
function parse($content, $extension)
{
    return getArrayByExt()[$extension]($content);
}

/**
 * @return array
 */
function getArrayByExt()
{
    return [
        'json' => function ($content) {
            return json_decode($content, true);
        },
        'yaml' => function ($content) {
            $parsed = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);

            return (array) $parsed;
        }
    ];
}
