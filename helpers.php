<?php

namespace Ravilushqa\Helpers;

/**
 * @param string $pathToFile
 * @return string
 * @throws \Exception
 */
function getFileExtension(string $pathToFile)
{
    if (!file_exists($pathToFile)) {
        throw new \Exception("file $pathToFile not found");
    }
    $info = new \SplFileInfo($pathToFile);
    return $info->getExtension();
}