<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\ValueObject;

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
    }

    public function testSubtract(): void
    {
        $amount = IntValue::fromNative(10);
        $this->assertEquals(13, $this->integer->subtract($amount)->toNative());
    }

    public function testToString(): void
    {
        $this->assertEquals((string)self::FIXED_NUM, (string)$this->integer);
    }

    protected function setUp(): void
    {
        $this->integer = IntValue::fromNative(self::FIXED_NUM);
    }
}
