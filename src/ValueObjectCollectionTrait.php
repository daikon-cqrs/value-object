<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Daikon\Interop\Assertion;
use Daikon\Interop\InvalidArgumentException;
use ReflectionClass;

trait ValueObjectCollectionTrait
{
    public function __construct(iterable $objects = [])
    {
        $validTypes = array_keys(static::getTypeFactories());
        $this->init($objects, $validTypes);
    }

    public static function makeEmpty(): self
    {
        return new static;
    }

    /** @param static $comparator */
    public function equals($comparator): bool
    {
        $this->assertInitialized();
        /**
         * @psalm-suppress RedundantConditionGivenDocblockType
         * @psalm-suppress DocblockTypeContradiction
         */
        Assertion::isInstanceOf(
            $comparator,
            static::class,
            sprintf(
                "Invalid comparator type '%s' given to ".static::class,
                is_object($comparator) ? get_class($comparator) : @gettype($comparator)
            )
        );

        /** @var ValueObjectInterface $object */
        foreach ($this as $index => $object) {
            $comparison = $comparator->get($index, null);
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
        $typeFactories = static::getTypeFactories();
        // Override fromNative() to support multiple types
        Assertion::count($typeFactories, 1, sprintf("Only 1 @type annotation is supported by '%s'.", static::class));
        /** @var array $typeFactory */
        $typeFactory = current($typeFactories);
        Assertion::isCallable($typeFactory, sprintf(
            "@type factory '%s' is not callable in '%s'.",
            implode('::', $typeFactory),
            static::class
        ));

        $objects = [];
        if (!is_null($state)) {
            foreach ($state as $key => $data) {
                $objects[$key] = $typeFactory($data);
            }
        }

        return new static($objects);
    }

    /** @return array */
    public function toNative()
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

    private static function getTypeFactories(): array
    {
        $classReflection = new ReflectionClass(static::class);
        if (!preg_match_all('#@type\s+(?<type>.+)#', (string)$classReflection->getDocComment(), $matches)) {
            throw new InvalidArgumentException(sprintf("Missing @type annotation on '%s'.", static::class));
        }

        $callables = [];
        foreach ($matches['type'] as $type) {
            $callable = array_map('trim', explode('::', $type));
            Assertion::keyNotExists(
                $callables,
                $callable[0],
                sprintf("Ambiguous @type annotation for '$callable[0]' in '%s'.", static::class)
            );
            if (!isset($callable[1])) {
                $callable[1] = 'fromNative';
            }
            $callables[$callable[0]] = $callable;
        }

        return $callables;
    }
}
