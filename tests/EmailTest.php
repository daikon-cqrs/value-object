<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\ValueObject;

use Daikon\ValueObject\Email;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    private const EMAIL = 'peter.parker@example.com';

    private Email $email;

    public function testToNative(): void
    {
        $this->assertEquals(self::EMAIL, $this->email->toNative());
        $this->assertEquals('', Email::fromNative(null)->toNative());
    }

    public function testEquals(): void
    {
        $sameEmail = Email::fromNative(self::EMAIL);
        $this->assertTrue($this->email->equals($sameEmail));
        $differentEmail = Email::fromNative('clark.kent@example.com');
        $this->assertFalse($this->email->equals($differentEmail));
    }

    public function testToString(): void
    {
        $this->assertEquals(self::EMAIL, (string)$this->email);
    }

    public function testGetLocalPart(): void
    {
        $this->assertEquals('peter.parker', (string)$this->email->getLocalPart());
    }

    public function testGetDomain(): void
    {
        $this->assertEquals('example.com', (string)$this->email->getDomain());
    }

    public function testMakeEmpty(): void
    {
        $email = Email::makeEmpty();
        $this->assertTrue($email->isEmpty());
        $this->assertNull($email->toNative());
        $this->assertEquals('', (string)$email);
    }

    protected function setUp(): void
    {
        $this->email = Email::fromNative(self::EMAIL);
    }
}
