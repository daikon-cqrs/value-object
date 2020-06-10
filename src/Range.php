<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Daikon\Interop\Assertion;

final class Range implements ValueObjectInterface
{
    private array $range;

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        Assertion::isInstanceOf($comparator, self::class);
        return $this->toNative() === $comparator->toNative();
    }

    /** @param array $value */
    public static function fromNative($value): self
    {
        Assertion::isArray($value, 'Trying to create Range from unsupported value type.');
        Assertion::count($value, 2, 'Range must have two values.');
        Assertion::allInteger($value, 'Range values are not integers.');

        $values = array_values($value);
        Assertion::greaterOrEqualThan($values[1], $values[0], 'Range end must be greater than or equal to start.');

        return new self($values);
    }

    public function toNative(): array
    {
        return $this->range;
    }

    public function getStart(): int
    {
        return $this->range[0];
    }

    public function getEnd(): int
    {
        return $this->range[1];
    }

    public function getSize(): int
    {
        return ($this->range[1] - $this->range[0]) + 1;
    }

    public function __toString(): string
    {
        return sprintf('[%d,%d]', $this->range[0], $this->range[1]);
    }

    private function __construct(array $range)
    {
        $this->range = $range;
    }
}
