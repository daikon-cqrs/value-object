<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Assert\Assertion;

final class BoolValue implements ValueObjectInterface
{
    private bool $value;

    /** @param bool $value */
    public static function fromNative($value): self
    {
        Assertion::boolean($value, 'Trying to create BoolValue VO from unsupported value type.');
        return new self($value);
    }

    public static function false(): self
    {
        return new self(false);
    }

    public static function true(): self
    {
        return new self(true);
    }

    public function toNative(): bool
    {
        return $this->value;
    }

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        Assertion::isInstanceOf($comparator, self::class);
        return $this->toNative() === $comparator->toNative();
    }

    public function __toString(): string
    {
        return $this->value ? 'true' : 'false';
    }

    public function isTrue(): bool
    {
        return $this->value === true;
    }

    public function isFalse(): bool
    {
        return $this->value === false;
    }

    public function negate(): self
    {
        $clone = clone $this;
        $clone->value = !$this->value;
        return $clone;
    }

    private function __construct(bool $value)
    {
        $this->value = $value;
    }
}
