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

final class GeoPoint implements ValueObjectInterface
{
    /** @var float[] */
    public const NULL_ISLAND = ['lon' => 0.0, 'lat' => 0.0];

    /** @var FloatValue */
    private $lon;

    /** var FloatValue */
    private $lat;

    /** @param float[] $point */
    public static function fromArray(array $point): self
    {
        Assertion::keyExists($point, 'lon');
        Assertion::keyExists($point, 'lat');
        return new self(FloatValue::fromNative($point['lon']), FloatValue::fromNative($point['lat']));
    }

    /** @param float[]|null $value */
    public static function fromNative($value): self
    {
        Assertion::nullOrIsArray($value, 'Trying to create GeoPoint VO from unsupported value type.');
        return is_array($value) ? self::fromArray($value) : self::fromArray(self::NULL_ISLAND);
    }

    public function toNative(): array
    {
        return ['lon' => $this->lon->toNative(), 'lat' => $this->lat->toNative()];
    }

    /** @param self $comparator */
    public function equals($comparator): bool
    {
        Assertion::isInstanceOf($comparator, self::class);
        return $this->toNative() == $comparator->toNative();
    }

    public function __toString(): string
    {
        return sprintf('lon: %s, lat: %s', $this->lon, $this->lat);
    }

    public function isNullIsland(): bool
    {
        return $this->toNative() == self::NULL_ISLAND;
    }

    public function getLon(): FloatValue
    {
        return $this->lon;
    }

    public function getLat(): FloatValue
    {
        return $this->lat;
    }

    private function __construct(FloatValue $lon, FloatValue $lat)
    {
        $this->lon = $lon;
        $this->lat = $lat;
    }
}
