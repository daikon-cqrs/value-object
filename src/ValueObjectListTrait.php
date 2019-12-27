<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Assert\Assertion;
use Ds\Vector;
use InvalidArgumentException;
use OutOfRangeException;
use ReflectionClass;
use RuntimeException;
use Traversable;
use UnderflowException;

trait ValueObjectListTrait
{
    /** @var Vector */
    private $compositeVector;

    /** @var callable */
    private $itemFactory;

    public function has(int $index): bool
    {
        return $this->compositeVector->offsetExists($index);
    }

    /** @throws OutOfRangeException */
    public function get(int $index): ValueObjectInterface
    {
        return $this->compositeVector->get($index);
    }

    /** @throws InvalidArgumentException */
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

    /** @throws InvalidArgumentException */
    public function remove(ValueObjectInterface $item): ValueObjectListInterface
    {
        $index = $this->indexOf($item);
        if ($index === false) {
            return $this;
        }
        $copy = clone $this;
        $copy->compositeVector->remove((int)$index);
        return $copy;
    }

    public function replace(ValueObjectInterface $item, ValueObjectInterface $replacement): ValueObjectListInterface
    {
        $index = $this->indexOf($item);
        if ($index === false) {
            throw new OutOfRangeException;
        }
        $copy = clone $this;
        $copy->compositeVector->remove((int)$index);
        $copy->compositeVector->insert((int)$index, $replacement);
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

    /** @throws InvalidArgumentException */
    public function indexOf(ValueObjectInterface $item)
    {
        $this->assertItemType($item);
        return $this->compositeVector->find($item);
    }

    /** @throws UnderflowException */
    public function getFirst(): ValueObjectInterface
    {
        return $this->compositeVector->first();
    }

    /** @throws UnderflowException */
    public function getLast(): ValueObjectInterface
    {
        return $this->compositeVector->last();
    }

    public function getIterator(): Traversable
    {
        return $this->compositeVector->getIterator();
    }

    private static function getItemFactory(): callable
    {
        $classReflection = new ReflectionClass(static::class);
        if (!preg_match('#@type\s+(?<type>.+)#', $classReflection->getDocComment(), $matches)) {
            throw new RuntimeException('Missing @type annotation on '.static::class);
        }
        $callable = array_map('trim', explode('::', $matches['type']));
        //@todo assume fromNative if not provided
        Assertion::isCallable($callable, $matches['type'].' is not callable in '.static::class);
        return $callable;
    }

    private function init(iterable $items): void
    {
        //@todo check if already initialized
        $this->itemFactory = static::getItemFactory();
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
            throw new InvalidArgumentException(sprintf(
                'Invalid item key given to %s. Expected int but was given %s.',
                static::class,
                is_object($index) ? get_class($index) : @gettype($index)
            ));
        }
    }

    /** @param ValueObjectInterface $item */
    private function assertItemType($item): void
    {
        /** @psalm-suppress InvalidArrayAccess */
        $typeClass = $this->itemFactory[0];
        if (!is_a($item, $typeClass)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid item type given to %s. Expected %s but was given %s.',
                static::class,
                $typeClass,
                is_object($item) ? get_class($item) : @gettype($item)
            ));
        }
    }

    private function __clone()
    {
        $this->compositeVector = new Vector($this->compositeVector->toArray());
    }

    private function __construct(iterable $items = [])
    {
        $this->init($items);
    }

    public static function makeEmpty(): ValueObjectListInterface
    {
        return new static;
    }

    public static function wrap(iterable $objects): ValueObjectListInterface
    {
        return new static($objects);
    }

    public function unwrap(): array
    {
        return $this->compositeVector->toArray();
    }

    /** @param null|iterable $payload */
    public static function fromNative($payload): ValueObjectListInterface
    {
        Assertion::nullOrIsTraversable($payload);
        if (is_null($payload)) {
            return static::makeEmpty();
        }

        $objects = [];
        $itemFactory = static::getItemFactory();
        foreach ($payload as $data) {
            $objects[] = call_user_func($itemFactory, $data);
        }

        return static::wrap($objects);
    }

    public function toNative(): array
    {
        return $this->compositeVector->map(
            /** @return mixed */
            function (ValueObjectInterface $object) {
                return $object->toNative();
            }
        )->toArray();
    }

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        if (!$comparator instanceof static || $this->count() !== $comparator->count()) {
            return false;
        }
        /** @var ValueObjectInterface $object */
        foreach ($this->compositeVector as $index => $object) {
            if (!$object->equals($comparator->get($index))) {
                return false;
            }
        }
        return true;
    }

    public function __toString(): string
    {
        $parts = [];
        foreach ($this->compositeVector as $object) {
            $parts[] = (string)$object;
        }
        return implode(', ', $parts);
    }
}
