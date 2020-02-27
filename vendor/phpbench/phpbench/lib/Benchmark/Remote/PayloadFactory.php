<?php

/*
 * This file is part of the PHPBench package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace PhpBench\Benchmark\Remote;

class PayloadFactory
{
    public function create($template, array $tokens = [], $phpBinary = null): Payload
    {
        return new Payload($template, $tokens, $phpBinary);
    }
}
