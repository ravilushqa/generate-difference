<?php

namespace Ravilushqa\Parser;


function format(array $data, $format)
{
    return getArrayByFormat()[$format]($data);
}

function getArrayByFormat()
{
    return [
        'pretty' => function (array $data) {
            return json_encode($data,JSON_PRETTY_PRINT);
        }
    ];
}