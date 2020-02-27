<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use Functional\Exceptions\InvalidArgumentException;
use Traversable;

/**
 * Returns true if all of the elements in the collection pass the callback falsy test. Opposite of Functional\all().
 * Callback arguments will be element, index, collection.
 *
 * @param Traversable|array $collection
 * @param callable $callback
 * @return bool
 */
function none($collection, callable $callback)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    foreach ($collection as $index => $element) {
        if ($callback($element, $index, $collection)) {
            return false;
        }
    }

    return true;
}
