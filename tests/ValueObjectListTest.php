<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 namespace Daikon\Tests\ValueObject;

use Daikon\Tests\ValueObject\Fixture\DateList;
use Daikon\ValueObject\Date;
use PHPUnit\Framework\TestCase;

final class DateListTest extends TestCase
{
    public function testConstruct(): void
    {
        $d0 = Date::fromString('2020-01-01');
        $unwrappedList = (new DateList([$d0]))->unwrap();
        $this->assertNotSame($d0, $unwrappedList[0]);
        $this->assertEquals($d0, $unwrappedList[0]);
    }

    public function testEquals(): void
    {
        $d0 = Date::fromString('2020-01-01');
        $list0 = new DateList([$d0]);
        $list1 = new DateList([$d0]);
        $this->assertTrue($list0->equals($list1));
        $this->assertNotSame($list0, $list1);
        $this->assertEquals($list0, $list1);
    }

    public function testNotEquals(): void
    {
        $d0 = Date::fromString('2020-01-01');
        $d1 = Date::fromString('2030-01-01');
        $d2 = clone $d1;
        $list0 = new DateList([$d0, $d1]);
        $list1 = new DateList([$d2, $d0]);
        $this->assertFalse($list0->equals($list1));
        $this->assertNotSame($list0, $list1);
    }

    public function testToString(): void
    {
        $d0 = Date::fromString('2020-01-01');
        $d1 = Date::fromString('2030-01-01');
        $list = new DateList([$d0, $d1]);
        $this->assertEquals('0:2020-01-01, 1:2030-01-01', (string)$list);
    }
}
