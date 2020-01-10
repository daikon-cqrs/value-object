<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\ValueObject;

use Daikon\ValueObject\Timestamp;
use PHPUnit\Framework\Error\Warning;
use PHPUnit\Framework\TestCase;

final class TimestampTest extends TestCase
{
    private const FIXED_TIMESTAMP_EUR = '2016-07-04T19:27:07.123000+02:00';

    private const FIXED_TIMESTAMP_UTC = '2016-07-04T17:27:07.123000+00:00';

    private const FIXED_EARLY_TIMESTAMP_UTC = '2016-07-03T17:17:07.122999+00:00';

    private const FIXED_LATE_TIMESTAMP_UTC = '2016-07-05T17:27:07.123000+00:00';

    private Timestamp $timestamp;

    public function testToNative(): void
    {
        $this->assertEquals(self::FIXED_TIMESTAMP_UTC, $this->timestamp->toNative());
        $this->assertEquals(null, Timestamp::fromNative(null)->toNative());
    }

    public function testEquals(): void
    {
        $equalTs = Timestamp::fromString('2016-07-04T17:27:07.123000', 'Y-m-d\\TH:i:s.u');
        $this->assertTrue($this->timestamp->equals($equalTs));
        $differentTs = Timestamp::fromString('+1 year', 'Y-m-d\\TH:i:s.u');
        $this->assertFalse($this->timestamp->equals($differentTs));
    }

    public function testToString(): void
    {
        $this->assertEquals(self::FIXED_TIMESTAMP_UTC, (string)$this->timestamp);
    }

    public function testToTime(): void
    {
        $this->assertEquals('1578687888', Timestamp::fromNative('1578687888')->toTime());
    }

    public function testIsNull(): void
    {
        $nullTs = Timestamp::fromNative(null);
        $this->assertTrue($nullTs->isNull());
    }

    public function testIsBefore(): void
    {
        $nullTs = Timestamp::fromNative(null);
        $earlyTs = Timestamp::fromString(self::FIXED_TIMESTAMP_UTC);
        $lateTs = Timestamp::fromString(self::FIXED_LATE_TIMESTAMP_UTC);
        $this->assertTrue($nullTs->isBefore($earlyTs));
        $this->assertFalse($earlyTs->isBefore($nullTs));
        $this->assertTrue($earlyTs->isBefore($lateTs));
        $this->assertFalse($lateTs->isBefore($earlyTs));
    }

    public function testIsAfter(): void
    {
        $nullTs = Timestamp::fromNative(null);
        $earlyTs = Timestamp::fromString(self::FIXED_TIMESTAMP_UTC);
        $lateTs = Timestamp::fromString(self::FIXED_LATE_TIMESTAMP_UTC);
        $this->assertFalse($nullTs->isAfter($earlyTs));
        $this->assertTrue($earlyTs->isAfter($nullTs));
        $this->assertFalse($earlyTs->isAfter($lateTs));
        $this->assertTrue($lateTs->isAfter($earlyTs));
    }

    public function testModify(): void
    {
        $addTs1 = $this->timestamp->modify('+1 day');
        $addTs2 = $this->timestamp->modify('+24 hours');
        $this->assertEquals(self::FIXED_LATE_TIMESTAMP_UTC, (string)$addTs1);
        $this->assertEquals(self::FIXED_LATE_TIMESTAMP_UTC, (string)$addTs2);

        $subTs1 = $this->timestamp->modify('-1 day - 10 minutes - 1 microsecond');
        $subTs2 = $this->timestamp->modify('-24 hours - 600 seconds - 1 microsecond');
        $this->assertEquals(self::FIXED_EARLY_TIMESTAMP_UTC, (string)$subTs1);
        $this->assertEquals(self::FIXED_EARLY_TIMESTAMP_UTC, (string)$subTs2);

        $this->expectException(Warning::class);
        $this->timestamp->modify('bogus');
    }

    protected function setUp(): void
    {
        $this->timestamp = Timestamp::fromNative(self::FIXED_TIMESTAMP_EUR);
    }
}
