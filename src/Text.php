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

final class Text implements MakeEmptyInterface, ValueObjectInterface
{
    private string $value;

    private string $encoding;

    /** @param null|string $value */
    public static function fromNative($value): self
    {
        Assertion::nullOrString($value, 'Trying to create Text VO from unsupported value type.');
        return is_null($value) ? new self : new self($value);
    }

    public static function makeEmpty(): self
    {
        return new self;
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
        return mb_strlen($this->value, $this->encoding) ?: 0;
    }

    // use mb_detect_order() to set runtime encoding detection options
    private function __construct(string $value = '', string $encoding = null)
    {
        $this->value = $value;
        $this->encoding = $encoding ?? mb_detect_encoding($value);
    }
}
