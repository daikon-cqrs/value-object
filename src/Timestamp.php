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
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;

final class Timestamp implements ValueObjectInterface
{
    /** @var string */
    public const NATIVE_FORMAT = 'Y-m-d\TH:i:s.uP';

    /** @var DateTimeImmutable|null */
    private $value;

    public static function now(): self
    {
        return new self(new DateTimeImmutable);
    }

    /** @param int|string $time */
    public static function fromTime($time): self
    {
        Assertion::integerish($time, 'Unix time must be an integer.');
        Assertion::greaterOrEqualThan((int)$time, 0, 'Unix time must be greater or equal than 0.');
        return new self(new DateTimeImmutable("@$time"));
    }

    public static function fromString(string $date, string $format = self::NATIVE_FORMAT): self
    {
        Assertion::date($date, $format);
        if (!$dateTime = DateTimeImmutable::createFromFormat($format, $date)) {
            throw new \RuntimeException('Invalid date string given to ' . self::class);
        }
        return new self($dateTime);
    }

    /** @param string|null $value */
    public static function fromNative($value): self
    {
        Assertion::nullOrString($value, 'Trying to create Timestamp VO from unsupported value type.');
        return empty($value) || $value === 'null' ? new self : self::fromString($value);
    }

    public function toNative(): ?string
    {
        return is_null($this->value) ? null : $this->value->format(self::NATIVE_FORMAT);
    }

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        Assertion::isInstanceOf($comparator, self::class);
        return $this->toNative() === $comparator->toNative();
    }

    public function isNull(): bool
    {
        return $this->value === null;
    }

    public function isBefore(self $comparand): bool
    {
        if ($this->isNull()) {
            return true;
        } elseif ($comparand->isNull()) {
            return false;
        } else {
            return $this->value < DateTimeImmutable::createFromFormat(self::NATIVE_FORMAT, (string)$comparand);
        }
    }

    public function isAfter(self $comparand): bool
    {
        if ($this->isNull()) {
            return false;
        } elseif ($comparand->isNull()) {
            return true;
        } else {
            return $this->value > DateTimeImmutable::createFromFormat(self::NATIVE_FORMAT, (string)$comparand);
        }
    }

    /** @param string $interval */
    public function modify($interval): self
    {
        Assertion::false($this->isNull(), 'Cannot modify null Timestamp.');
        Assertion::string($interval);
        Assertion::notEmpty($interval);

        $modified = $this->value->modify($interval);
        Assertion::isInstanceOf($modified, DateTimeImmutable::class, 'Invalid modification interval.');

        return new self($modified);
    }

    public function __toString(): string
    {
        return $this->toNative() ?? 'null';
    }

    private function __construct(DateTimeImmutable $value = null)
    {
        $this->value = $value ? $value->setTimezone(new DateTimeZone('UTC')) : $value;
    }
}
