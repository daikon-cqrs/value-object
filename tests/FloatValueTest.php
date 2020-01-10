<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\ValueObject;

use Daikon\ValueObject\FloatValue;
use PHPUnit\Framework\TestCase;

final class FloatValueTest extends TestCase
{
    private const FIXED_DEC = 2.3;

    private FloatValue $float;

    public function testToNative(): void
    {
        $this->assertEquals(self::FIXED_DEC, $this->float->toNative());
        $this->assertNull(FloatValue::fromNative(null)->toNative());
    }

    public function testEquals(): void
    {
        $sameNumber = FloatValue::fromNative(self::FIXED_DEC);
        $this->assertTrue($this->float->equals($sameNumber));
        $differentNumber = FloatValue::fromNative(4.2);
        $this->assertFalse($this->float->equals($differentNumber));
    }

    public function testToString(): void
    {
        $this->assertEquals((string)self::FIXED_DEC, (string)$this->float);
        $this->assertEquals('null', (string)FloatValue::fromNative(null));
    }

    protected function setUp(): void
    {
        $this->float = FloatValue::fromNative(self::FIXED_DEC);
    }
}
