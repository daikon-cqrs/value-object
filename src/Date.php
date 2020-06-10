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
use DateTimeImmutable;

final class Date implements ValueObjectInterface
{
    public const NATIVE_FORMAT = 'Y-m-d';

    private ?DateTimeImmutable $value;

    public static function today(): self
    {
        return new self(new DateTimeImmutable);
    }

    public static function fromString(string $value, string $format = self::NATIVE_FORMAT): self
    {
        Assertion::date($value, $format);
        if (!$date = DateTimeImmutable::createFromFormat($format, $value)) {
            throw new InvalidArgumentException('Invalid date string given to '.self::class);
        }
        return new self($date);
    }

    /** @param string|null $value */
    public static function fromNative($value): self
    {
        Assertion::nullOrString($value, 'Trying to create Date VO from unsupported value type.');
        return empty($value) ? new self : self::fromString($value);
    }

    public function toNative(): ?string
    {
        return is_null($this->value) ? null : $this->value->format(static::NATIVE_FORMAT);
    }

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        Assertion::isInstanceOf($comparator, self::class);
        return $this->toNative() === $comparator->toNative();
    }

    public function __toString(): string
    {
        return $this->toNative() ?? '';
    }

    private function __construct(DateTimeImmutable $value = null)
    {
        $this->value = $value ? $value->setTime(0, 0, 0) : $value;
    }
}
