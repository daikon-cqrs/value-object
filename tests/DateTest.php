<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\ValueObject;

use Daikon\ValueObject\Date;
use PHPUnit\Framework\TestCase;

final class DateTest extends TestCase
{
    private const DATE = '2016-07-04';

    /** @var Date */
    private $date;

    public function testToNative(): void
    {
        $this->assertEquals(self::DATE, $this->date->toNative());
        $this->assertNull(Date::fromNative(null)->toNative());
    }

    public function testEquals(): void
    {
        $sameDate = Date::fromNative(self::DATE);
        $this->assertTrue($this->date->equals($sameDate));
        $sameDateOtherFormat = Date::fromString('2016-07-04T19:27:07', 'Y-m-d\\TH:i:s');
        $this->assertTrue($this->date->equals($sameDateOtherFormat));
        $differentDate = Date::fromNative('2017-08-10');
        $this->assertFalse($this->date->equals($differentDate));
    }

    public function testToString(): void
    {
        $this->assertEquals(self::DATE, (string)$this->date);
        $this->assertEquals('', (string)Date::fromNative(null));
    }

    protected function setUp(): void
    {
        $this->date = Date::fromNative(self::DATE);
    }
}
