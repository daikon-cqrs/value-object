<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/value-object project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Tests\ValueObject;

use Daikon\ValueObject\Url;
use PHPUnit\Framework\TestCase;

final class UrlTest extends TestCase
{
    private const FIXED_URL = 'https://www.example.com:8080/?param=value#q=trellis';

    /** @var Url */
    private $url;

    public function testToNative(): void
    {
        $this->assertEquals(self::FIXED_URL, $this->url->toNative());
        $this->assertEquals('', Url::fromNative(null)->toNative());
    }

    public function testEquals(): void
    {
        $this->assertTrue($this->url->equals(Url::fromNative(self::FIXED_URL)));
        $this->assertFalse($this->url->equals(Url::fromNative('http://example.com')));
    }

    public function testToString(): void
    {
        $this->assertEquals(self::FIXED_URL, (string)$this->url);
    }

    public function testGetScheme(): void
    {
        $this->assertEquals('https', $this->url->getScheme()->toNative());
    }

    public function testGetHost(): void
    {
        $this->assertEquals('www.example.com', $this->url->getHost()->toNative());
    }

    public function testGetPort(): void
    {
        $this->assertEquals(8080, $this->url->getPort()->toNative());
    }

    public function testGetPath(): void
    {
        $this->assertEquals('/', $this->url->getPath()->toNative());
    }

    public function testGetQuery(): void
    {
        $this->assertEquals('param=value', $this->url->getQuery()->toNative());
    }

    public function testGetFragment(): void
    {
        $this->assertEquals('q=trellis', $this->url->getFragment()->toNative());
    }

    protected function setUp(): void
    {
        $this->url = Url::fromNative(self::FIXED_URL);
    }
}
