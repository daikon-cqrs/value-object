<?php
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\ValueObject;

use Assert\Assertion;
use Ds\Vector;

trait ValueObjectListTrait
{
    /** @var Vector */
    private $compositeVector;

    /** @var string */
    private $itemType;

    public function has(int $index): bool
    {
        return $this->compositeVector->offsetExists($index);
    }

    /** @throws \OutOfRangeException */
    public function get(int $index): ValueObjectInterface
    {
        return $this->compositeVector->get($index);
    }

    /** @throws \InvalidArgumentException */
    public function push(ValueObjectInterface $item): ValueObjectListInterface
    {
        $this->assertItemType($item);
        $copy = clone $this;
        $copy->compositeVector->push($item);
        return $copy;
    }

    /** @throws InvalidArgumentException */
    public function unshift(ValueObjectInterface $item): ValueObjectListInterface
    {
        $this->assertItemType($item);
        $copy = clone $this;
        $copy->compositeVector->unshift($item);
        return $copy;
    }

    /** @throws \InvalidArgumentException */
    public function remove(ValueObjectInterface $item): ValueObjectListInterface
    {
        $index = $this->indexOf($item);
        if ($index === false) {
            return $this;
        }
        $copy = clone $this;
        $copy->compositeVector->remove($index);
        return $copy;
    }

    public function reverse(): ValueObjectListInterface
    {
        $copy = clone $this;
        $copy->compositeVector->reverse();
        return $copy;
    }

    public function count(): int
    {
        return $this->compositeVector->count();
    }

    public function isEmpty(): bool
    {
        return $this->compositeVector->isEmpty();
    }

    /**
     * @return int|bool
     * @throws \InvalidArgumentException
     */
    public function indexOf(ValueObjectInterface $item)
    {
        $this->assertItemType($item);
        return $this->compositeVector->find($item);
    }

    public function getFirst(): ValueObjectInterface
    {
        return $this->compositeVector->first();
    }

    public function getLast(): ValueObjectInterface
    {
        return $this->compositeVector->last();
    }

    /** @psalm-suppress MoreSpecificReturnType */
    public function getIterator(): \Iterator
    {
        return $this->compositeVector->getIterator();
    }

    public function getItemType(): string
    {
        return $this->itemType;
    }

    private function init(iterable $items, string $itemType): void
    {
        Assertion::notEmpty($itemType);
        $this->itemType = $itemType;
        foreach ($items as $index => $item) {
            $this->assertItemIndex($index);
            $this->assertItemType($item);
        }
        $this->compositeVector = new Vector($items);
    }

    /** @param mixed $index */
    private function assertItemIndex($index): void
    {
        if (!is_int($index)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid item key given to %s. Expected int but was given %s.',
                static::class,
                is_object($index) ? get_class($index) : @gettype($index)
            ));
        }
    }

    /** @param mixed $item */
    private function assertItemType($item): void
    {
        if (empty($this->itemType)) {
            throw new \RuntimeException('Item type has not been specified.');
        }

        if (!is_a($item, $this->itemType)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid item type given to %s. Expected %s but was given %s.',
                static::class,
                $this->itemType,
                is_object($item) ? get_class($item) : @gettype($item)
            ));
        }
    }

    private function __clone()
    {
        $this->compositeVector = new Vector($this->compositeVector->toArray());
    }

    public static function makeEmpty(): ValueObjectListInterface
    {
        return new static;
    }

    public static function wrap($objects): ValueObjectListInterface
    {
        return new static($objects);
    }

    public function unwrap(): array
    {
        return $this->compositeVector->toArray();
    }

    /** @param null|array $payload */
    public static function fromNative($payload): ValueObjectListInterface
    {
        Assertion::nullOrIsArray($payload);
        if (is_null($payload)) {
            return static::makeEmpty();
        }
        $objects = [];
        foreach ($payload as $object) {
            $objects[] = call_user_func([$this->itemType, 'fromNative'], $object);
        }
        return static::wrap($objects);
    }

    public function toNative(): array
    {
        return $this->compositeVector->map(function (ValueObjectInterface $object): array {
            return (array)$object->toNative();
        })->toArray();
    }

    public function equals(ValueObjectListInterface $list): bool
    {
        if (!$list instanceof static || $this->count() !== $list->count()) {
            return false;
        }
        /** @var ValueObjectInterface $object */
        foreach ($this->compositeVector as $index => $object) {
            if (!$object->equals($list->get($index))) {
                return false;
            }
        }
        return true;
    }

    public function __toString(): string
    {
        $parts = [];
        foreach ($this as $object) {
            $parts[] = (string)$object;
        }
        return implode(', ', $parts);
    }
}