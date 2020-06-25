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
use Daikon\Interop\MakeEmptyInterface;
use DateTimeImmutable;
use DateTimeZone;

final class Timestamp implements MakeEmptyInterface, ValueObjectInterface
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

    public static function makeEmpty(): self
    {
        return new self;
    }

    public function toTime(): int
    {
        Assertion::false($this->isEmpty(), 'Cannot convert empty timestamp.');
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
                throw new InvalidArgumentException('Invalid timestamp.');
            }
        }

        return new self($dateTime);
    }

    /** @param null|int|string $value */
    public static function fromNative($value): self
    {
        Assertion::nullOrSatisfy(
            $value,
            /** @param mixed $value */
            fn($value): bool => is_string($value) || is_numeric($value),
            'Invalid timestamp.'
        );

        return empty($value) || $value === 'null' ? new self : self::fromString((string)$value);
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

    public function isEmpty(): bool
    {
        return $this->value === null;
    }

    public function isBefore(self $comparand): bool
    {
        if ($this->isEmpty()) {
            return true;
        } elseif ($comparand->isEmpty()) {
            return false;
        } else {
            return $this->value < DateTimeImmutable::createFromFormat(self::NATIVE_FORMAT, (string)$comparand);
        }
    }

    public function isAfter(self $comparand): bool
    {
        if ($this->isEmpty()) {
            return false;
        } elseif ($comparand->isEmpty()) {
            return true;
        } else {
            return $this->value > DateTimeImmutable::createFromFormat(self::NATIVE_FORMAT, (string)$comparand);
        }
    }

    /** @param string $interval */
    public function modify($interval): self
    {
        Assertion::false($this->isEmpty(), 'Cannot modify empty Timestamp.');
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
