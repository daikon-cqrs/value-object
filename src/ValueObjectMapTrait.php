<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Assert\Assertion;
use Ds\Map;
use InvalidArgumentException;
use OutOfBoundsException;
use ReflectionClass;
use RuntimeException;
use Traversable;

/*
 * @note this trait currently only supports a single type map as we don't have a clear
 * solution for mapping multiple types to keys on construction from a native array.
 */
trait ValueObjectMapTrait
{
    /** @var Map */
    private $compositeMap;

    /** @var callable */
    private $itemFactory;

    public function has(string $key): bool
    {
        return $this->compositeMap->hasKey($key);
    }

    /** @throws OutOfBoundsException */
    public function get(string $key): ValueObjectInterface
    {
        return $this->compositeMap->get($key);
    }

    /** @throws InvalidArgumentException */
    public function set(string $key, ValueObjectInterface $item): ValueObjectMapInterface
    {
        $this->assertItemType($item);
        $copy = clone $this;
        $copy->compositeMap->put($key, $item);
        return $copy;
    }

    public function count(): int
    {
        return count($this->compositeMap);
    }

    public function toNative(): array
    {
        $clone = clone $this;
        $clone->compositeMap->apply(
            /** @return mixed */
            function (string $key, ValueObjectInterface $object) {
                return $object->toNative();
            }
        );
        return $clone->compositeMap->toArray();
    }

    /** @param null|iterable $payload */
    public static function fromNative($payload): ValueObjectMapInterface
    {
        Assertion::nullOrIsTraversable($payload);
        if (is_null($payload)) {
            return static::makeEmpty();
        }

        $objects = [];
        $itemFactory = static::getItemFactory();
        foreach ($payload as $key => $data) {
            $objects[$key] = call_user_func($itemFactory, $data);
        }
        return static::wrap($objects);
    }

    public function isEmpty(): bool
    {
        return $this->compositeMap->isEmpty();
    }

    public function getIterator(): Traversable
    {
        return $this->compositeMap->getIterator();
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
        foreach ($items as $key => $item) {
            $this->assertItemKey($key);
            $this->assertItemType($item);
        }
        $this->compositeMap = new Map($items);
    }

    /** @param mixed $key */
    private function assertItemKey($key): void
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid item key given to %s. Expected string but was given %s.',
                static::class,
                is_object($key) ? get_class($key) : @gettype($key)
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

    private function __construct(iterable $items = [])
    {
        $this->init($items);
    }

    public function __clone()
    {
        $this->compositeMap = new Map($this->compositeMap->toArray());
    }

    /** @param iterable $objects */
    public static function wrap($objects): ValueObjectMapInterface
    {
        return new static($objects);
    }

    public static function makeEmpty(): ValueObjectMapInterface
    {
        return new static;
    }

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        if (!$comparator instanceof static || $this->count() !== $comparator->count()) {
            return false;
        }
        /** @var ValueObjectInterface $object */
        foreach ($this->compositeMap as $key => $object) {
            if (!$object->equals($comparator->get($key))) {
                return false;
            }
        }
        return true;
    }

    public function __toString(): string
    {
        $parts = [];
        foreach ($this->compositeMap as $key => $object) {
            $parts[] = $key.': '.$object;
        }
        return implode(', ', $parts);
    }
}
