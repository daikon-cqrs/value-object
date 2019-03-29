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
use Ramsey\Uuid\Uuid as RamseyUuid;
use Ramsey\Uuid\UuidInterface;

final class Uuid implements ValueObjectInterface
{
    /** @var UuidInterface|null */
    private $value;

    public static function generate(): Uuid
    {
        return new self(RamseyUuid::uuid4());
    }

    /** @param string|null $value */
    public static function fromNative($value): Uuid
    {
        Assertion::nullOrString($value, 'Trying to create Uuid VO from unsupported value type.');
        return empty($value) ? new self : new self(RamseyUuid::fromString($value));
    }

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        return $comparator instanceof self && $this->toNative() === $comparator->toNative();
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
