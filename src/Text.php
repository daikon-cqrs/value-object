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

    /** @param string|null $value */
    public static function fromNative($value): Text
    {
        Assertion::nullOrString($value, 'Trying to create Text VO from unsupported value type.');
        return is_null($value) ? new self : new self($value);
    }

    /** @param self $value */
    public function equals($value): bool
    {
        return $value instanceof self && $this->toNative() === $value->toNative();
    }

    public function toNative(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toNative();
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function getLength(): int
    {
        return strlen($this->value);
    }

    private function __construct(string $value = '')
    {
        $this->value = $value;
    }
}
