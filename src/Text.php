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

final class Text implements ValueObjectInterface
{
    /** @var string */
    private $value;

    /** @var string */
    private $encoding;

    /** @param string|null $value */
    public static function fromNative($value): self
    {
        Assertion::nullOrString($value, 'Trying to create Text VO from unsupported value type.');
        return is_null($value) ? new self : new self($value);
    }

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        Assertion::isInstanceOf($comparator, self::class);
        return $this->toNative() === $comparator->toNative();
    }

    public function toNative(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isEmpty(): bool
    {
        return $this->value === '';
    }

    public function getLength(): int
    {
        return mb_strlen($this->value, $this->encoding);
    }

    // use mb_detect_order() to set runtime encoding detection options
    private function __construct(string $value = '', string $encoding = null)
    {
        $this->value = $value;
        $this->encoding = $encoding ?? mb_detect_encoding($value);
    }
}
