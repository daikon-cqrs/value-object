<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Assert\Assertion;

final class Email implements ValueObjectInterface
{
    private const EMPTY = '';

    /** @var Text */
    private $localPart;

    /** @var Text */
    private $domain;

    /** @param null|string $value */
    public static function fromNative($value): self
    {
        Assertion::nullOrString($value, 'Trying to create Email VO from unsupported value type.');
        if (empty($value)) {
            return new self(Text::fromNative(self::EMPTY), Text::fromNative(self::EMPTY));
        }
        Assertion::email($value, 'Trying to create email from invalid string.');
        $parts = explode('@', $value);
        return new self(Text::fromNative($parts[0]), Text::fromNative(trim($parts[1], '[]')));
    }

    public function toNative(): string
    {
        if ($this->localPart->isEmpty() && $this->domain->isEmpty()) {
            return self::EMPTY;
        }
        return $this->localPart->toNative().'@'.$this->domain->toNative();
    }

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        Assertion::isInstanceOf($comparator, self::class);
        return $this->toNative() === $comparator->toNative();
    }

    public function __toString(): string
    {
        return $this->toNative();
    }

    public function getLocalPart(): Text
    {
        return $this->localPart;
    }

    public function getDomain(): Text
    {
        return $this->domain;
    }

    private function __construct(Text $localPart, Text $domain)
    {
        $this->localPart = $localPart;
        $this->domain = $domain;
    }
}
