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

final class IntValue implements ValueObjectInterface
{
    /** @var int|null */
    private $value;

    public function isZero(): bool
    {
        return $this->value === 0;
    }

    public function isNull(): bool
    {
        return is_null($this->value);
    }

    public function add(self $amount): self
    {
        Assertion::false($this->isNull() || $amount->isNull(), 'Operands must not be null.');
        /** @psalm-suppress PossiblyNullOperand */
        return self::fromNative($this->toNative() + $amount->toNative());
    }

    public function subtract(self $amount): self
    {
        Assertion::false($this->isNull() || $amount->isNull(), 'Operands must not be null.');
        /** @psalm-suppress PossiblyNullOperand */
        return self::fromNative($this->toNative() - $amount->toNative());
    }

    public function isGreaterThanOrEqualTo(self $comparator): bool
    {
        return $this->toNative() >= $comparator->toNative();
    }

    public function isLessThanOrEqualTo(self $comparator): bool
    {
        return $this->toNative() <= $comparator->toNative();
    }

    public function isGreaterThan(self $comparator): bool
    {
        return $this->toNative() > $comparator->toNative();
    }

    public function isLessThan(self $comparator): bool
    {
        return $this->toNative() < $comparator->toNative();
    }

    public static function zero(): self
    {
        return new self(0);
    }

    /** @param int|string|null $value  */
    public static function fromNative($value): self
    {
        $value = $value === '' ? null : $value;
        Assertion::nullOrIntegerish($value, 'Trying to create IntValue VO from unsupported value type.');
        return new self(is_null($value) ? null : (int)$value);
    }

    public function toNative(): ?int
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
        return is_null($this->value) ? 'null' : (string)$this->value;
    }

    private function __construct(?int $value)
    {
        $this->value = $value;
    }
}
