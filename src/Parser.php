<?php

namespace Ravilushqa\Parser;

use function Ravilushqa\Helpers\getFileExtension;

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
        }
    ];
}
