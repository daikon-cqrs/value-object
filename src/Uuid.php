<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\ValueObject;

use Daikon\Interop\Assertion;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Ramsey\Uuid\UuidInterface;

final class Uuid implements ValueObjectInterface
{
    private ?UuidInterface $value;

    public static function generate(): self
    {
        return new self(RamseyUuid::uuid4());
    }

    /** @param null|string $value */
    public static function fromNative($value): self
    {
        Assertion::nullOrString($value, 'Trying to create Uuid VO from unsupported value type.');
        return empty($value) ? new self : new self(RamseyUuid::fromString($value));
    }

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        Assertion::isInstanceOf($comparator, self::class);
        return $this->toNative() === $comparator->toNative();
    }

    public function toNative(): ?string
    {
        return $this->value ? $this->value->toString() : $this->value;
    }

    public function __toString(): string
    {
        return $this->value ? $this->value->toString() : 'null';
    }

    private function __construct(UuidInterface $value = null)
    {
        $this->value = $value;
    }
}
