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
        throw new \Exception("file $pathToFile not found\n");
    }
    $info = new \SplFileInfo($pathToFile);
    return $info->getExtension();
}

/**
 * @param string $pathToFile
 * @return bool|string
 * @throws \Exception
 */
function getFileContent(string $pathToFile)
{
    if (file_exists($pathToFile) && is_readable($pathToFile)) {
        $content = file_get_contents($pathToFile);
    } else {
        throw new \Exception("file '{$pathToFile}' is undefined");
    }
    if ($content === false) {
        throw new \Exception("file '{$pathToFile}' is undefined");
    }
    return $content;
}