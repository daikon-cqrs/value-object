<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\ValueObject;

use Daikon\ValueObject\BoolValue;
use PHPUnit\Framework\TestCase;

final class BooleanTest extends TestCase
{
    public function testToNative(): void
    {
        $this->assertTrue(BoolValue::fromNative(true)->toNative());
        $this->assertFalse(BoolValue::fromNative(false)->toNative());
    }

    public function testEquals(): void
    {
        $bool = BoolValue::fromNative(true);
        $this->assertTrue($bool->equals(BoolValue::fromNative(true)));
        $this->assertFalse($bool->equals(BoolValue::fromNative(false)));
    }

    public function testIsTrue(): void
    {
        $this->assertTrue(BoolValue::fromNative(true)->isTrue());
    }

    public function testIsFalse(): void
    {
        $this->assertTrue(BoolValue::fromNative(false)->isFalse());
    }

    public function testNegate(): void
    {
        $this->assertTrue(BoolValue::fromNative(false)->negate()->toNative());
    }

    public function testToString(): void
    {
        $this->assertEquals('true', (string)BoolValue::fromNative(true));
        $this->assertEquals('false', (string)BoolValue::fromNative(false));
    }
}
