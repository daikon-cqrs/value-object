<?php
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\ValueObject;

interface ValueObjectMapInterface extends ValueObjectInterface, \IteratorAggregate, \Countable
{
    public static function makeEmpty(): ValueObjectMapInterface;

    public static function wrap($objects): ValueObjectMapInterface;

    public function has(string $key): bool;

    public function get(string $key);

    public function set(string $key, $item): ValueObjectMapInterface;

    public function count(): int;

    public function isEmpty(): bool;

    public function getIterator(): \Iterator;
}
