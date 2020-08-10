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

final class FloatValue implements MakeEmptyInterface, ValueObjectInterface
{
    private ?float $value;

    /** @param null|int|float $value */
    public static function fromNative($value): self
    {
        if (is_integer($value)) {
            $value = floatval($value);
        }
        Assertion::nullOrFloat($value, 'Trying to create FloatValue VO from unsupported value type.');
        /** @psalm-suppress PossiblyInvalidArgument */
        return is_null($value) ? new self : new self(floatval($value));
    }

    public static function zero(): self
    {
        return new self(0.0);
    }

    public static function makeEmpty(): self
    {
        return new self;
    }

    public function format(int $decimals = 0, string $point = '.', string $separator = ','): string
    {
        return number_format($this->value, $decimals, $point, $separator);
    }

    public function isEmpty(): bool
    {
        return is_null($this->value);
    }

    public function isZero(): bool
    {
        return $this->value === 0.0;
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

    private function __construct(?float $value = null)
    {
        $this->value = $value;
    }
}
