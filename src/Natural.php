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

final class Natural implements MakeEmptyInterface, ValueObjectInterface
{
    private ?int $value;

    public function isZero(): bool
    {
        $this->assertNotEmpty();
        return $this->value === 0;
    }

    public function isEmpty(): bool
    {
        return $this->value === null;
    }

    public function add(self $amount): self
    {
        $this->assertNotEmpty();
        Assertion::false($amount->isEmpty(), 'Addition must not be empty.');
        return self::fromNative((int)$this->toNative() + (int)$amount->toNative());
    }

    public function subtract(self $amount): self
    {
        $this->assertNotEmpty();
        Assertion::false($amount->isEmpty(), 'Subtraction must not be empty.');
        return self::fromNative((int)$this->toNative() - (int)$amount->toNative());
    }

    public function isGreaterThanOrEqualTo(self $comparator): bool
    {
        $this->assertNotEmpty();
        Assertion::false($comparator->isEmpty(), 'Comparator must not be empty.');
        return $this->toNative() >= $comparator->toNative();
    }

    public function isLessThanOrEqualTo(self $comparator): bool
    {
        $this->assertNotEmpty();
        Assertion::false($comparator->isEmpty(), 'Comparator must not be empty.');
        return $this->toNative() <= $comparator->toNative();
    }

    public function isGreaterThan(self $comparator): bool
    {
        $this->assertNotEmpty();
        Assertion::false($comparator->isEmpty(), 'Comparator must not be empty.');
        return $this->toNative() > $comparator->toNative();
    }

    public function isLessThan(self $comparator): bool
    {
        $this->assertNotEmpty();
        Assertion::false($comparator->isEmpty(), 'Comparator must not be empty.');
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
        Assertion::nullOrIntegerish($value, 'Trying to create Natural VO from unsupported value type.');
        return $value === null ? new self : new self((int)$value);
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
        return (string)$this->value;
    }

    private function assertNotEmpty(): void
    {
        Assertion::false($this->isEmpty(), 'Natural is empty.');
    }

    private function __construct(int $value = null)
    {
        Assertion::nullOrGreaterOrEqualThan($value, 0, 'Must be at least 0.');
        $this->value = $value;
    }
}
