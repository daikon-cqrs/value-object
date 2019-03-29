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
use DateTimeImmutable;

final class Date implements ValueObjectInterface
{
    /** @var string */
    public const NATIVE_FORMAT = 'Y-m-d';

    /** @var DateTimeImmutable|null */
    private $value;

    public static function today(): Date
    {
        return new self(new DateTimeImmutable);
    }

    public static function createFromString(string $value, string $format = self::NATIVE_FORMAT): self
    {
        Assertion::date($value, $format);
        if (!$date = DateTimeImmutable::createFromFormat($format, $value)) {
            throw new \RuntimeException('Invalid date string given to ' . self::class);
        }
        return new self($date);
    }

    /** @param string|null $value */
    public static function fromNative($value): Date
    {
        Assertion::nullOrString($value, 'Trying to create Date VO from unsupported value type.');
        return empty($value) ? new self : self::createFromString($value);
    }

    public function toNative(): ?string
    {
        return is_null($this->value) ? null : $this->value->format(static::NATIVE_FORMAT);
    }

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        return $comparator instanceof self && $this->toNative() === $comparator->toNative();
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
