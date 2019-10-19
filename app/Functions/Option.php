<?php declare(strict_types = 1);

namespace App\Functions;

use PhpOption\Option;

if ( ! function_exists('option')) {
    /**
     * Вспомогательная функция.
     * Не вызывает ошибку во время передачи параметра, но
     * вызывет ошибку, если переданное значение == false
     *
     * @param $var
     *
     * @return Option
     */
    function option(&$var): Option
    {
        return Option::fromReturn(static function () use (&$var) {
            return $var;
        });
    }
}
