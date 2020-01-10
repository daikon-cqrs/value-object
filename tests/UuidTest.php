<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\ValueObject;

use Daikon\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

final class UuidTest extends TestCase
{
    private const FIXED_UUID = '110ec58a-a0f2-4ac4-8393-c866d813b8d1';

    private Uuid $uuid;

    public function testToNative(): void
    {
        $this->assertEquals(null, Uuid::fromNative(null)->toNative());
        $this->assertEquals(self::FIXED_UUID, $this->uuid->toNative());
    }

    public function testEquals(): void
    {
        $this->assertTrue($this->uuid->equals(Uuid::fromNative(self::FIXED_UUID)));
        $this->assertFalse($this->uuid->equals(Uuid::generate()));
        $this->assertFalse($this->uuid->equals(Uuid::fromNative(null)));
    }

    public function testString(): void
    {
        $this->assertEquals(self::FIXED_UUID, (string)$this->uuid);
    }

    protected function setUp(): void
    {
        $this->uuid = Uuid::fromNative(self::FIXED_UUID);
    }
}
