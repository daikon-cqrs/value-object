<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\ValueObject;

use Daikon\Interop\InvalidArgumentException;
use Daikon\ValueObject\Natural;
use PHPUnit\Framework\TestCase;

final class NaturalTest extends TestCase
{
    private const FIXED_NUM = 23;

    private Natural $natural;
    
    public function testToNative(): void
    {
        $this->assertEquals(self::FIXED_NUM, $this->natural->toNative());
        $this->assertNull(Natural::fromNative(null)->toNative());
    }

    public function testFromNative(): void
    {
        $this->assertNull(Natural::fromNative(null)->toNative());
        $this->assertNull(Natural::fromNative('')->toNative());
        $this->assertSame(0, Natural::fromNative('0')->toNative());
        $this->expectException(InvalidArgumentException::class);
        Natural::fromNative(-1);
    }

    public function testMakeEmpty(): void
    {
        $empty = Natural::makeEmpty();
        $this->assertNull($empty->toNative());
        $this->assertSame('', (string)$empty);
        $this->assertTrue($empty->isEmpty());
    }

    public function testZero(): void
    {
        $zero = Natural::zero();
        $this->assertSame(0, $zero->toNative());
        $this->assertSame('0', (string)$zero);
        $this->assertTrue($zero->isZero());
    }

    public function testEquals(): void
    {
        $sameNumber = Natural::fromNative(self::FIXED_NUM);
        $this->assertTrue($this->natural->equals($sameNumber));
        $differentNumber = Natural::fromNative(42);
        $this->assertFalse($this->natural->equals($differentNumber));
    }

    public function testAdd(): void
    {
        $amount = Natural::fromNative(10);
        $this->assertEquals(33, $this->natural->add($amount)->toNative());
    }

    public function testAddWithEmpty(): void
    {
        $amount = Natural::fromNative(10);
        $this->expectException(InvalidArgumentException::class);
        $amount->add(Natural::makeEmpty());
    }

    public function testAddOnEmpty(): void
    {
        $amount = Natural::fromNative(10);
        $this->expectException(InvalidArgumentException::class);
        Natural::makeEmpty()->add($amount);
    }

    public function testSubtract(): void
    {
        $amount = Natural::fromNative(10);
        $this->assertEquals(13, $this->natural->subtract($amount)->toNative());
    }

    public function testSubtractWithEmpty(): void
    {
        $amount = Natural::fromNative(10);
        $this->expectException(InvalidArgumentException::class);
        $amount->subtract(Natural::makeEmpty());
    }

    public function testSubtractOnEmpty(): void
    {
        $amount = Natural::fromNative(10);
        $this->expectException(InvalidArgumentException::class);
        Natural::makeEmpty()->subtract($amount);
    }

    public function testSubtractToInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Natural::zero()->subtract(Natural::fromNative(1));
    }

    public function testToString(): void
    {
        $this->assertEquals((string)self::FIXED_NUM, (string)$this->natural);
        $this->assertSame('', (string)Natural::makeEmpty());
    }

    protected function setUp(): void
    {
        $this->natural = Natural::fromNative(self::FIXED_NUM);
    }
}
