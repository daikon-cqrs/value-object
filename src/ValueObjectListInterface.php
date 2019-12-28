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

interface ValueObjectListInterface extends ValueObjectInterface, IteratorAggregate, Countable
{
    public static function makeEmpty(): ValueObjectListInterface;

    public static function wrap(iterable $objects): ValueObjectListInterface;

    public function has(int $index): bool;

    public function get(int $index): ValueObjectInterface;

    public function push(ValueObjectInterface $item): ValueObjectListInterface;

    public function unshift(ValueObjectInterface $item): ValueObjectListInterface;

    public function remove(ValueObjectInterface $item): ValueObjectListInterface;

    public function replace(ValueObjectInterface $item, ValueObjectInterface $replacement): ValueObjectListInterface;

    public function reverse(): ValueObjectListInterface;

    public function count(): int;

    public function isEmpty(): bool;

    /** @return int|bool */
    public function indexOf(ValueObjectInterface $item);

    public function getFirst(): ValueObjectInterface;

    public function getLast(): ValueObjectInterface;

    public function unwrap(): array;

    public function getIterator(): Traversable;
}
