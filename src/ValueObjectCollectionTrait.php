<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Daikon\Interop\Assertion;
use Daikon\Interop\SupportsAnnotations;

/**
 * @type(Daikon\ValueObject\ValueObjectInterface)
 */
trait ValueObjectCollectionTrait
{
    use SupportsAnnotations;

    /** @return static */
    public static function makeEmpty(): self
    {
        return new static;
    }

    public function isEmpty(): bool
    {
        return count($this) === 0;
    }

    /** @param static $comparator */
    public function equals($comparator): bool
    {
        $this->assertInitialized();
        Assertion::isInstanceOf($comparator, static::class);

        /** @var ValueObjectInterface $object */
        foreach ($this as $key => $object) {
            $comparison = $comparator->get($key, null);
            if (!$comparison || !$object->equals($comparison)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param null|iterable $state
     * @return static
     */
    public static function fromNative($state): self
    {
        Assertion::nullOrIsTraversable($state, 'State provided to '.static::class.' must be null or iterable.');
        // Override fromNative() to support multiple types, currently first seen type factory is used.
        $typeFactory = current(static::inferTypeFactories());
        Assertion::isCallable($typeFactory, 'No valid type factory specified.');
        /** @var callable $typeFactory */
        $objects = [];
        if (!is_null($state)) {
            foreach ($state as $key => $data) {
                $objects[$key] = $typeFactory($data);
            }
        }

        return new static($objects);
    }

    public function toNative(): array
    {
        $this->assertInitialized();
        $objects = [];
        foreach ($this as $key => $object) {
            $objects[$key] = $object->toNative();
        }
        return $objects;
    }

    public function __toString(): string
    {
        $this->assertInitialized();
        $parts = [];
        foreach ($this as $key => $object) {
            $parts[] = $key.':'.(string)$object;
        }
        return implode(', ', $parts);
    }
}
