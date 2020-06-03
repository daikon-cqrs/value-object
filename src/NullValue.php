<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Assert\Assertion;

final class NullValue implements ValueObjectInterface
{
    /** @param null|string $value  */
    public static function fromNative($value): self
    {
        Assertion::nullOrRegex($value, '#^$#', 'Trying to create NullValue VO from unsupported value.');
        return new self;
    }

    public static function makeEmpty(): self
    {
        return new self;
    }

    /** @return null */
    public function toNative()
    {
        return null;
    }

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        Assertion::isInstanceOf($comparator, self::class);
        return $this->toNative() === $comparator->toNative();
    }

    public function __toString(): string
    {
        return '';
    }
}
