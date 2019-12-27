<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Countable;
use IteratorAggregate;
use Traversable;

interface ValueObjectMapInterface extends ValueObjectInterface, IteratorAggregate, Countable
{
    public static function makeEmpty(): ValueObjectMapInterface;

    public static function wrap(iterable $objects): ValueObjectMapInterface;

    public function has(string $key): bool;

    public function get(string $key): ValueObjectInterface;

    public function set(string $key, ValueObjectInterface $item): ValueObjectMapInterface;

    public function count(): int;

    public function isEmpty(): bool;

    public function getIterator(): Traversable;
}
