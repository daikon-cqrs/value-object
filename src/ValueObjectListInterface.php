<?php
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\ValueObject;

interface ValueObjectListInterface extends ValueObjectInterface, \IteratorAggregate, \Countable
{
    public static function makeEmpty(): ValueObjectListInterface;

    public static function wrap($objects): ValueObjectListInterface;

    public function has(int $index): bool;

    public function get(int $index): ValueObjectInterface;

    public function push(ValueObjectInterface $item): ValueObjectListInterface;

    public function unshift(ValueObjectInterface $item): ValueObjectListInterface;

    public function remove(ValueObjectInterface $item): ValueObjectListInterface;

    public function reverse(): ValueObjectListInterface;

    public function count(): int;

    public function isEmpty(): bool;

    public function indexOf(ValueObjectInterface $item);

    public function getFirst(): ?ValueObjectInterface;

    public function getLast(): ?ValueObjectInterface;

    public function unwrap(): array;

    public function getIterator(): \Iterator;
}
