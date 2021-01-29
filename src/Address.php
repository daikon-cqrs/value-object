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

final class Address implements ValueObjectInterface, MakeEmptyInterface
{
    private ?string $name;

    private ?string $address1;

    private ?string $address2;

    private ?string $city;

    private ?string $postcode;

    private ?string $country;

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        Assertion::isInstanceOf($comparator, self::class);
        return $this->toNative() === $comparator->toNative();
    }

    /** @param array $value */
    public static function fromNative($value): self
    {
        Assertion::isArray($value, 'Trying to create address from unsupported value type.');
        return new self(
            $value['name'] ?? null,
            $value['address1'] ?? null,
            $value['address2'] ?? null,
            $value['city'] ?? null,
            $value['postcode'] ?? null,
            $value['country'] ?? null
        );
    }

    public function toNative(): array
    {
        return array_filter([
            'name' => $this->name,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'city' => $this->city,
            'postcode' => $this->postcode,
            'country' => $this->country
        ]);
    }

    public static function makeEmpty(): self
    {
        return new self;
    }

    public function isEmpty(): bool
    {
        return empty($this->toNative());
    }

    public function __toString(): string
    {
        return implode(PHP_EOL, $this->toNative());
    }

    private function __construct(
        string $name = null,
        string $address1 = null,
        string $address2 = null,
        string $city = null,
        string $postcode = null,
        string $country = null
    ) {
        $this->name = $name;
        $this->address1 = $address1;
        $this->address2 = $address2;
        $this->city = $city;
        $this->postcode = $postcode;
        $this->country = $country;
    }
}
