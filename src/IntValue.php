<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Daikon\Interop\Assertion;
use Daikon\Interop\MakeEmptyInterface;

final class IntValue implements MakeEmptyInterface, ValueObjectInterface
{
    private ?int $value;

    public function isZero(): bool
    {
        return $this->value === 0;
    }

    public function isEmpty(): bool
    {
        return is_null($this->value);
    }

    public function add(self $amount): self
    {
        Assertion::false($this->isEmpty() || $amount->isEmpty(), 'Operands must not be null.');
        /** @psalm-suppress PossiblyNullOperand */
        return self::fromNative($this->toNative() + $amount->toNative());
    }

    public function subtract(self $amount): self
    {
        Assertion::false($this->isEmpty() || $amount->isEmpty(), 'Operands must not be null.');
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

    public static function makeEmpty(): self
    {
        return new self;
    }

    public static function zero(): self
    {
        return new self(0);
    }

    /** @param null|int|string $value  */
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
        return $this->isEmpty() ? '' : (string)$this->value;
    }

    private function __construct(int $value = null)
    {
        $this->value = $value;
    }
}
