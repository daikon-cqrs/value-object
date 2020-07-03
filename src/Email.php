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

final class Email implements MakeEmptyInterface, ValueObjectInterface
{
    private Text $localPart;

    private Text $domain;

    /** @param null|string $value */
    public static function fromNative($value): self
    {
        Assertion::nullOrString($value, 'Trying to create Email VO from unsupported value type.');
        if (empty($value)) {
            return self::makeEmpty();
        }
        Assertion::email($value, 'Trying to create email from invalid string.');
        $parts = explode('@', $value);
        return new self(Text::fromNative($parts[0]), Text::fromNative($parts[1]));
    }

    public static function makeEmpty(): self
    {
        return new self(Text::makeEmpty(), Text::makeEmpty());
    }

    public function toNative(): ?string
    {
        return $this->isEmpty() ? null : $this->localPart.'@'.$this->domain;
    }

    public function isEmpty(): bool
    {
        return $this->localPart->isEmpty() || $this->domain->isEmpty();
    }

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        Assertion::isInstanceOf($comparator, self::class);
        return $this->toNative() === $comparator->toNative();
    }

    public function __toString(): string
    {
        return (string)$this->toNative();
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
