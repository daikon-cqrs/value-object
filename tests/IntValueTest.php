<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\ValueObject;

use Daikon\Interop\InvalidArgumentException;
use Daikon\ValueObject\IntValue;
use PHPUnit\Framework\TestCase;

final class IntValueTest extends TestCase
{
    private const FIXED_NUM = 23;

    private IntValue $integer;
    
    public function testToNative(): void
    {
        $this->assertEquals(self::FIXED_NUM, $this->integer->toNative());
        $this->assertNull(IntValue::fromNative(null)->toNative());
    }

    public function testFromNative(): void
    {
        $this->assertEquals(null, IntValue::fromNative(null)->toNative());
        $this->assertEquals(null, IntValue::fromNative('')->toNative());
        $this->assertEquals(0, IntValue::fromNative('0')->toNative());
        $this->assertEquals(-1, IntValue::fromNative(-1)->toNative());
    }

    public function testMakeEmpty(): void
    {
        $empty = IntValue::makeEmpty();
        $this->assertNull($empty->toNative());
        $this->assertEquals('', (string)$empty);
        $this->assertTrue($empty->isEmpty());
    }

    public function testZero(): void
    {
        $zero = IntValue::zero();
        $this->assertEquals(0, $zero->toNative());
        $this->assertEquals('0', (string)$zero);
    }

    public function testEquals(): void
    {
        $sameNumber = IntValue::fromNative(self::FIXED_NUM);
        $this->assertTrue($this->integer->equals($sameNumber));
        $differentNumber = IntValue::fromNative(42);
        $this->assertFalse($this->integer->equals($differentNumber));
    }

    public function testAdd(): void
    {
        $amount = IntValue::fromNative(10);
        $this->assertEquals(33, $this->integer->add($amount)->toNative());
        $this->expectException(InvalidArgumentException::class);
        $amount->add(IntValue::makeEmpty());
    }

    public function testSubtract(): void
    {
        $amount = IntValue::fromNative(10);
        $this->assertEquals(13, $this->integer->subtract($amount)->toNative());
        $this->expectException(InvalidArgumentException::class);
        $amount->subtract(IntValue::makeEmpty());
    }

    public function testToString(): void
    {
        $this->assertEquals((string)self::FIXED_NUM, (string)$this->integer);
        $this->assertEquals('', (string)IntValue::makeEmpty());
    }

    protected function setUp(): void
    {
        $this->integer = IntValue::fromNative(self::FIXED_NUM);
    }
}
