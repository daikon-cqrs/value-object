<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\ValueObject;

use Daikon\ValueObject\GeoPoint;
use PHPUnit\Framework\TestCase;

final class GeoPointTest extends TestCase
{
    private const COORDS = [
        'lon' => 13.413215,
        'lat' => 52.521918
    ];

    /** @var GeoPoint */
    private $geoPoint;

    public function testToNative(): void
    {
        $this->assertEquals(GeoPoint::NULL_ISLAND, GeoPoint::fromNative(null)->toNative());
        $this->assertEquals(self::COORDS, $this->geoPoint->toNative());
    }

    public function testEquals(): void
    {
        $samePoint = GeoPoint::fromNative(self::COORDS);
        $this->assertTrue($this->geoPoint->equals($samePoint));
        $differentPoint = GeoPoint::fromNative([ 'lon' => 12.11716, 'lat' => 49.248 ]);
        $this->assertFalse($this->geoPoint->equals($differentPoint));
    }

    public function testGetLon(): void
    {
        $this->assertEquals(self::COORDS['lon'], $this->geoPoint->getLon()->toNative());
    }

    public function testGetLat(): void
    {
        $this->assertEquals(self::COORDS['lat'], $this->geoPoint->getLat()->toNative());
    }

    public function testToString(): void
    {
        $this->assertEquals(
            sprintf('lon: %f, lat: %f', self::COORDS['lon'], self::COORDS['lat']),
            (string)$this->geoPoint
        );
    }

    protected function setUp(): void
    {
        $this->geoPoint = GeoPoint::fromNative(self::COORDS);
    }
}
