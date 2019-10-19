<?php declare(strict_types = 1);

namespace App\Functions;

/**
 * Convert a multi-dimensional array into a single-dimensional array.
 *
 * @param  array  $array  The multi-dimensional array.
 *
 * @return array|bool
 * @author Sean Cannon, LitmusBox.com | seanc@litmusbox.com
 */
function array_flatten(array $array): ?array
{
    if ( ! \is_array($array)) {
        return false;
    }

    $result = [];

    foreach ($array as $key => $value) {
        if (\is_array($value)) {
            $result = array_merge($result, array_flatten($value));
        } else {
            $result[$key] = $value;
        }
    }

    return $result;
}