<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\ValueObject;

use Daikon\Interop\InvalidArgumentException;
use Daikon\ValueObject\FloatValue;
use PHPUnit\Framework\TestCase;

final class FloatValueTest extends TestCase
{
    private const FIXED_DEC = 2.3;

    public function testToNative(): void
    {
        $float = FloatValue::fromNative(self::FIXED_DEC);
        $this->assertSame(self::FIXED_DEC, $float->toNative());
        $float = FloatValue::fromNative(null);
        $this->assertNull($float->toNative());
        $float = FloatValue::makeEmpty();
        $this->assertNull($float->toNative());
        $float = FloatValue::zero();
        $this->assertSame(0.0, $float->toNative());
        $float = FloatValue::fromNative(1);
        $this->assertSame(1.0, $float->toNative());
    }

    public function testEquals(): void
    {
        $float = FloatValue::fromNative(self::FIXED_DEC);
        $sameNumber = FloatValue::fromNative(self::FIXED_DEC);
        $differentNumber = FloatValue::fromNative(4.2);
        $this->assertTrue($float->equals($sameNumber));
        $this->assertFalse($float->equals($differentNumber));
    }

    public function testIsZero(): void
    {
        $float = FloatValue::fromNative(self::FIXED_DEC);
        $this->assertFalse($float->isZero());
        $float = FloatValue::zero();
        $this->assertTrue($float->isZero());
        $float = FloatValue::fromNative(0);
        $this->assertTrue($float->isZero());
        $this->assertTrue($float->isZero());
        $float = FloatValue::fromNative(0.0);
        $this->assertTrue($float->isZero());
        $this->expectException(InvalidArgumentException::class);
        $float = FloatValue::fromNative('0');
    }

    public function testIsEmpty(): void
    {
        $float = FloatValue::makeEmpty();
        $this->assertTrue($float->isEmpty());
        $float = FloatValue::fromNative(null);
        $this->assertTrue($float->isEmpty());
        $float = FloatValue::fromNative(0);
        $this->assertFalse($float->isEmpty());
    }

    public function testToString(): void
    {
        $float = FloatValue::fromNative(self::FIXED_DEC);
        $this->assertEquals((string)self::FIXED_DEC, (string)$float);
        $float = FloatValue::fromNative(null);
        $this->assertEquals('null', (string)$float);
        $float = FloatValue::makeEmpty();
        $this->assertEquals('null', (string)$float);
        $float = FloatValue::zero();
        $this->assertEquals('0', (string)$float);
        $this->markTestIncomplete('This handling needs to be fixed.');
        $float = FloatValue::fromNative(10.0);
        $this->assertEquals('10.0', (string)$float);
    }
}
