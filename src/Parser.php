<?php

namespace Ravilushqa\Parser;

use function Ravilushqa\Helpers\getFileExtension;

function parse($filePath)
{
    return getArrayByExt()[getFileExtension($filePath)](file_get_contents($filePath));
}

function getArrayByExt()
{
    return [
        'json' => function ($file) {
            return json_decode($file, true);
        }
    ];
}