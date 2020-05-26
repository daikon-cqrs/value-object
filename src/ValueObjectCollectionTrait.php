<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Assert\Assert;
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
        Assert::that($comparator)->isInstanceOf(static::class, sprintf(
            "Invalid comparator type '%s' given to ".static::class,
            is_object($comparator) ? get_class($comparator) : @gettype($comparator)
        ));

        /** @var ValueObjectInterface $object */
        foreach ($this as $index => $object) {
            $comparison = $comparator->get($index, null);
            if (!$comparison || !$object->equals($comparison)) {
                return false;
            }
        }

        return true;
    }

    /** @param null|iterable $state */
    public static function fromNative($state): self
    {
        Assert::that($state)->nullOr()->isTraversable('State provided to '.static::class.' must be null or iterable');
        $typeFactories = static::getTypeFactories();
        // Override fromNative() to support multiple types
        Assert::that($typeFactories)->count(1, 'Only one @type annotation is supported by '.static::class);
        /** @var array $typeFactory */
        $typeFactory = current($typeFactories);
        Assert::that($typeFactory)->isArray()->isCallable(sprintf(
            "@type annotation '%s' is not callable in ".static::class,
            implode('::', $typeFactory)
        ));

        $objects = [];
        if (!is_null($state)) {
            foreach ($state as $key => $data) {
                $objects[$key] = call_user_func($typeFactory, $data);
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
        $match = (bool)preg_match_all(
            '#@type\s+(?<type>.+)#',
            (string)$classReflection->getDocComment(),
            $matches
        );
        Assert::that($match)->true('Missing @type annotation on '.static::class);

        $callables = [];
        foreach ($matches['type'] as $type) {
            $callable = array_map('trim', explode('::', $type));
            Assert::that($callables)->keyNotExists(
                $callable[0],
                "Ambiguous @type annotation for '$callable[0]' in ".static::class
            );
            if (!isset($callable[1])) {
                $callable[1] = 'fromNative';
            }
            $callables[$callable[0]] = $callable;
        }

        return $callables;
    }
}
