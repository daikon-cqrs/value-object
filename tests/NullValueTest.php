<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\ValueObject;

use Daikon\Interop\InvalidArgumentException;
use Daikon\ValueObject\NullValue;
use PHPUnit\Framework\TestCase;

final class NullValueTest extends TestCase
{
    private NullValue $nullValue;

    public function testToNative(): void
    {
        $this->assertNull($this->nullValue->toNative());
        $this->assertNull(NullValue::fromNative(null)->toNative());
        $this->assertNull(NullValue::fromNative('')->toNative());

        $this->expectException(InvalidArgumentException::class);
        NullValue::fromNative('xyz');
    }

    public function testEquals(): void
    {
        $this->assertTrue($this->nullValue->equals(NullValue::fromNative('')));
    }

    public function testToString(): void
    {
        $this->assertEquals('', (string)$this->nullValue);
    }

    protected function setUp(): void
    {
        $this->nullValue = NullValue::makeEmpty();
    }
}
