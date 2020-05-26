<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Assert\Assertion;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;

final class Timestamp implements ValueObjectInterface
{
    public const NATIVE_FORMAT = 'Y-m-d\TH:i:s.uP';

    private ?DateTimeImmutable $value;

    public static function now(): self
    {
        return new self(new DateTimeImmutable);
    }

    public static function epoch(): self
    {
        return new self((new DateTimeImmutable)->setTimestamp(0));
    }

    /** @param int|string $time */
    public static function fromTime($time): self
    {
        Assertion::integerish($time, 'Unix time must be an integer.');
        Assertion::greaterOrEqualThan((int)$time, 0, 'Unix time must be greater or equal than 0.');
        return new self(new DateTimeImmutable("@$time"));
    }

    public function toTime(): int
    {
        Assertion::false($this->isNull(), 'Cannot convert null to time.');
        /** @psalm-suppress PossiblyNullReference */
        return $this->value->getTimestamp();
    }

    public static function fromString(string $date, string $format = self::NATIVE_FORMAT): self
    {
        if ($date === 'now') {
            return self::now();
        }

        if ($date === 'epoch') {
            return self::epoch();
        }

        if (is_numeric($date)) {
            $format = strpos($date, '.') ? 'U.u' : 'U';
        }

        if (!$dateTime = DateTimeImmutable::createFromFormat($format, $date)) {
            $time = strtotime($date);
            if ($time === false || !$dateTime = new DateTimeImmutable('@'.$time)) {
                throw new InvalidArgumentException('Invalid date string given to '.self::class);
            }
        }

        return new self($dateTime);
    }

    /** @param null|string $value */
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
        /** @psalm-suppress PossiblyNullReference */
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
