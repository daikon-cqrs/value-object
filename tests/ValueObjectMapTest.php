<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 namespace Daikon\Tests\ValueObject;

use Daikon\Tests\ValueObject\Fixture\DateMap;
use Daikon\ValueObject\Date;
use PHPUnit\Framework\TestCase;

final class DateMapTest extends TestCase
{
    public function testConstruct(): void
    {
        $d0 = Date::fromString('2020-01-01');
        $unwrappedMap = (new DateMap(['a' => $d0]))->unwrap();
        $this->assertNotSame($d0, $unwrappedMap['a']);
        $this->assertEquals($d0, $unwrappedMap['a']);
    }

    public function testFromNative(): void
    {
        $unwrappedMap = DateMap::fromNative(['a' => '2020-01-01'])->unwrap();
        $this->assertCount(1, $unwrappedMap);
        $this->assertInstanceOf(Date::class, $unwrappedMap['a']);
    }

    public function testEquals(): void
    {
        $d0 = Date::fromString('2020-01-01');
        $map0 = new DateMap(['a' => $d0]);
        $map1 = new DateMap(['a' => $d0]);
        $this->assertTrue($map0->equals($map1));
        $this->assertNotSame($map0, $map1);
        $this->assertEquals($map0, $map1);
    }

    public function testNotEquals(): void
    {
        $d0 = Date::fromString('2020-01-01');
        $d1 = Date::fromString('2030-01-01');
        $map0 = new DateMap(['a' => $d0, 'b' => $d1]);
        $map1 = new DateMap(['a' => $d1, 'b' => $d0]);
        $this->assertFalse($map0->equals($map1));
        $this->assertNotSame($map0, $map1);
    }

    public function testToString(): void
    {
        $d0 = Date::fromString('2020-01-01');
        $d1 = Date::fromString('2030-01-01');
        $map = new DateMap(['a' => $d0, 'b' => $d1]);
        $this->assertEquals('a:2020-01-01, b:2030-01-01', (string)$map);
    }
}
