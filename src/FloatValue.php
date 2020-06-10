<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Daikon\Interop\Assertion;

final class FloatValue implements ValueObjectInterface
{
    private ?float $value;

    public function isNull(): bool
    {
        return is_null($this->value);
    }

    /** @param null|float $value */
    public static function fromNative($value): self
    {
        Assertion::nullOrFloat($value, 'Trying to create FloatValue VO from unsupported value type.');
        /** @psalm-suppress PossiblyInvalidArgument */
        return new self($value);
    }

    public function toNative(): ?float
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

    private function __construct(?float $value)
    {
        $this->value = $value;
    }
}
